<?php

namespace App\Models;

use App\Enum\RoleEnum;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser
{
  use HasFactory, HasRoles, Notifiable;

  protected $fillable = [
    'name',
    'email',
    'password',
    'role',
    'is_active',
    'role',
    'is_active',
  ];

  protected $hidden = [
    'password',
    'remember_token',
  ];

  protected $casts = [
    'email_verified_at' => 'datetime',
    'password' => 'hashed',
  ];

  // Relationships
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

  public function canAccessPanel(Panel $panel): bool
  {
    return Auth::check() && (Auth::user()->hasRole(RoleEnum::ADMIN->value) || Auth::user()->hasRole(RoleEnum::PETUGAS->value));
  }
}
