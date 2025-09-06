<?php

namespace App\Models;

use App\Enum\RoleEnum;
use Carbon\Carbon;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser, MustVerifyEmail, CanResetPassword
{
  use HasFactory, HasRoles, Notifiable;

  protected $fillable = [
    'name',
    'email',
    'password',
    'is_active',
    'activated_at',
    'expires_at',
  ];

  protected $hidden = [
    'password',
    'remember_token',
  ];

  protected $casts = [
    'email_verified_at' => 'datetime',
    'password' => 'hashed',
    'is_active' => 'boolean',
    'activated_at' => 'datetime',
    'expires_at' => 'datetime',
  ];

  protected static function boot()
  {
    parent::boot();

    static::created(function ($user) {});

    static::saving(function ($user) {
      if ($user->is_active && $user->isDirty('is_active') && $user->getOriginal('is_active') == false) {
        $user->activated_at = now();
        $user->expires_at = now()->addYears(3);
      }

      if (!$user->is_active && $user->isDirty('is_active')) {
        $user->activated_at = null;
        $user->expires_at = null;
      }
    });
  }

  public function profile()
  {
    return $this->hasOne(Profile::class);
  }

  public function loans()
  {
    return $this->hasMany(Loan::class);
  }

  public function fines()
  {
    return $this->hasMany(Fine::class);
  }

  public function visitLogs()
  {
    return $this->hasMany(VisitLog::class);
  }

  public function donations()
  {
    return $this->hasMany(Donation::class);
  }

  public function announcements()
  {
    return $this->hasMany(Announcement::class);
  }

  public function cart(): HasMany
  {
    return $this->hasMany(Cart::class);
  }

  public function getIsExpiredAttribute(): bool
  {
    if ($this->hasRole([RoleEnum::ADMIN->value, RoleEnum::PETUGAS->value])) {
      return false;
    }

    return $this->expires_at && $this->expires_at->isPast();
  }

  public function getDaysUntilExpiryAttribute(): ?int
  {
    if ($this->hasRole([RoleEnum::ADMIN->value, RoleEnum::PETUGAS->value])) {
      return null;
    }

    if (!$this->expires_at) {
      return null;
    }

    return max(0, now()->diffInDays($this->expires_at, false));
  }

  public function getExpiryStatusAttribute(): string
  {
    try {
      if (!$this->relationLoaded('roles')) {
        $this->load('roles');
      }

      if ($this->hasRole(RoleEnum::ADMIN->value)) {
        return 'Admin';
      }

      if ($this->hasRole(RoleEnum::PETUGAS->value)) {
        return 'Petugas';
      }

      if (!$this->is_active) {
        return 'Tidak Aktif';
      }

      if (!$this->expires_at) {
        return 'Belum Diatur';
      }

      if ($this->is_expired) {
        return 'Kadaluarsa';
      }

      $daysLeft = $this->days_until_expiry;

      if ($daysLeft && $daysLeft <= 30) {
        return "Akan Kadaluarsa ({$daysLeft} hari)";
      }

      return 'Aktif';
    } catch (\Exception $e) {
      return 'Error';
    }
  }

  public function getExpiryStatusColorAttribute(): string
  {
    try {
      if (!$this->relationLoaded('roles')) {
        $this->load('roles');
      }

      if ($this->hasRole([RoleEnum::ADMIN->value, RoleEnum::PETUGAS->value])) {
        return 'info';
      }

      if (!$this->is_active) {
        return 'gray';
      }

      if ($this->is_expired) {
        return 'danger';
      }

      $daysLeft = $this->days_until_expiry;

      if ($daysLeft && $daysLeft <= 30) {
        return 'warning';
      }

      return 'success';
    } catch (\Exception $e) {
      return 'gray';
    }
  }

  public function extendAccount(int $years = 3): void
  {
    if ($this->hasRole([RoleEnum::ADMIN->value, RoleEnum::PETUGAS->value])) {
      return;
    }


    if ($this->is_active && $this->hasRole(RoleEnum::SISWA->value)) {
      $this->expires_at = $this->expires_at
        ? $this->expires_at->addYears($years)
        : now()->addYears($years);
      $this->save();
    }
  }

  public static function checkAndDeactivateExpiredUsers(): int
  {
    $expiredUsers = static::where('is_active', true)
      ->where('expires_at', '<=', now())
      ->whereHas('roles', function ($query) {
        $query->where('name', RoleEnum::SISWA->value);
      })
      ->get();

    $count = 0;
    foreach ($expiredUsers as $user) {
      $user->update(['is_active' => false]);
      $count++;
    }

    return $count;
  }

  public function canAccessPanel(Panel $panel): bool
  {
    //    if ($this->hasRole([RoleEnum::ADMIN->value, RoleEnum::PETUGAS->value])) {
    //      return true;
    //    }
    //
    //    if ($this->is_expired) {
    //      $this->update(['is_active' => false]);
    //      return false;
    //    }
    //
    //    return $this->is_active;

    return true;
  }

  public function isStaff(): bool
  {
    try {
      return $this->hasRole([RoleEnum::ADMIN->value, RoleEnum::PETUGAS->value]);
    } catch (\Exception $e) {
      return false;
    }
  }

  public function isStudent(): bool
  {
    try {
      return $this->hasRole(RoleEnum::SISWA->value);
    } catch (\Exception $e) {
      return false;
    }
  }

  public function getLibraryCardData(): array
  {
    $profile = $this->profile;

    return [
      'name' => $this->name,
      'nis' => $profile?->nis ?? '-',
      'class' => $profile?->class ?? '-',
      'gender' => $profile?->gender ?? '-',
      'photo' => $profile?->photo_url ?? null,
      'barcode_data' => $profile?->nis ?? $this->id,
      'member_since' => $this->activated_at?->format('Y') ?? date('Y'),
    ];
  }

  public function canPrintLibraryCard(): bool
  {
    try {
      return $this->isStudent() && $this->is_active;
    } catch (\Exception $e) {
      return false;
    }
  }
}
