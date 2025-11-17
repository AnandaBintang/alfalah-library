<?php

namespace App\Filament\Resources;

use App\Enum\RoleEnum;
use App\Filament\Imports\UserWithProfileImporter;
use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use App\Models\Profile;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Spatie\Permission\Models\Role;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;
use PhpOffice\PhpSpreadsheet\IOFactory;

class UserResource extends Resource
{
  protected static ?string $model = User::class;
  protected static ?string $navigationIcon = 'heroicon-o-users';
  protected static ?string $navigationLabel = 'User';

  public static function getNavigationGroup(): ?string
  {
    return 'Manajemen User';
  }

  public static function form(Form $form): Form
  {
    return $form
      ->schema([
        Forms\Components\Section::make('Informasi Dasar')
          ->schema([
            Forms\Components\TextInput::make('name')
              ->label('Nama')
              ->required()
              ->maxLength(255),

            Forms\Components\TextInput::make('email')
              ->label('Email')
              ->email()
              ->required()
              ->unique(User::class, 'email', ignoreRecord: true)
              ->maxLength(255),

            Forms\Components\TextInput::make('password')
              ->label('Password')
              ->password()
              ->required(fn($livewire) => $livewire instanceof Pages\CreateUser)
              ->minLength(8)
              ->maxLength(255)
              ->dehydrateStateUsing(fn($state) => $state ? Hash::make($state) : null)
              ->dehydrated(fn($state) => filled($state)),

            Forms\Components\Select::make('role')
              ->label('Role')
              ->options(Role::pluck('name', 'name')->toArray())
              ->required()
              ->live()
              ->afterStateUpdated(function ($state, $set) {
                if (in_array($state, [RoleEnum::ADMIN->value, RoleEnum::PETUGAS->value])) {
                  $set('is_active', true);
                }
              })
              ->dehydrated(false),
          ])
          ->columns(2),

        Forms\Components\Section::make('Status Aktivasi')
          ->schema([
            Forms\Components\Toggle::make('is_active')
              ->label('Aktif')
              ->default(false)
              ->disabled(fn(Forms\Get $get) => in_array($get('role'), [RoleEnum::ADMIN->value, RoleEnum::PETUGAS->value]))
              ->helperText(
                fn(Forms\Get $get) => in_array($get('role'), [RoleEnum::ADMIN->value, RoleEnum::PETUGAS->value])
                  ? 'Admin dan Petugas tidak perlu aktivasi manual.'
                  : 'Siswa perlu aktivasi manual. Masa berlaku 3 tahun sejak aktivasi.'
              ),

            Forms\Components\DateTimePicker::make('expires_at')
              ->label('Masa Berlaku Hingga')
              ->disabled()
              ->visible(fn(Forms\Get $get) => $get('role') === RoleEnum::SISWA->value)
              ->helperText('Otomatis diset 3 tahun untuk siswa.'),
          ])
          ->columns(2),

        Forms\Components\Section::make('Profil Siswa')
          ->relationship('profile') // langsung bind ke relasi hasOne profile
          ->schema([
            Forms\Components\TextInput::make('user_code')
              ->label('Kode User')
              ->required(),

            Forms\Components\TextInput::make('nis')
              ->label('NIS')
              ->maxLength(20),

            Forms\Components\TextInput::make('nisn')
              ->label('NISN')
              ->maxLength(20),

            Forms\Components\TextInput::make('class')
              ->label('Kelas')
              ->maxLength(50),

            Forms\Components\TextInput::make('address')
              ->label('Alamat')
              ->maxLength(255),

            Forms\Components\TextInput::make('phone')
              ->label('No HP')
              ->tel(),

            Forms\Components\Select::make('gender')
              ->label('Jenis Kelamin')
              ->options([
                'L' => 'Laki-laki',
                'P' => 'Perempuan',
              ]),
          ])
          ->columns(2),
      ]);

  }

  public static function table(Table $table): Table
  {
    return $table
      ->modifyQueryUsing(function (Builder $query) {
        return $query
          ->with('roles')
          ->leftJoin('model_has_roles', function ($join) {
            $join->on('users.id', '=', 'model_has_roles.model_id')
              ->where('model_has_roles.model_type', '=', \App\Models\User::class);
          })
          ->leftJoin('roles', 'model_has_roles.role_id', '=', 'roles.id')
          ->leftJoin('profiles', 'users.id', '=', 'profiles.user_id')
          ->select([
            'users.*',
            'roles.name as role_name',
            'profiles.nis as profile_nis',
            'profiles.nisn as profile_nisn',
            'profiles.class as profile_class',
            'profiles.address as profile_address',
            'profiles.phone as profile_phone',
          ]);
      })
      ->columns(static::getTableColumns())
      ->filters(static::getTableFilters())
      ->headerActions(static::getTableHeaderActions())
      ->actions(static::getTableActions())
      ->bulkActions(static::getTableBulkActions())
      ->defaultSort('role_name', 'asc')
      ->defaultSort('id', 'desc');
  }

  protected static function getTableColumns(): array
  {
    return [
      Tables\Columns\TextColumn::make('name')
        ->label('Nama')
        ->sortable()
        ->searchable(),

      Tables\Columns\TextColumn::make('email')
        ->label('Email')
        ->sortable()
        ->searchable(),

      Tables\Columns\TextColumn::make('role_name')
        ->label('Role')
        ->sortable()
        ->searchable(),

      Tables\Columns\TextColumn::make('profile_nis')
        ->label('NIS')
        ->getStateUsing(fn($record) => $record?->profile_nis ?? '-'),

      Tables\Columns\TextColumn::make('profile_class')
        ->label('Kelas')
        ->getStateUsing(fn($record) => $record?->profile_class ?? '-'),

      Tables\Columns\TextColumn::make('expiry_status')
        ->label('Status')
        ->badge()
        ->color(static::getExpiryStatusColor(...))
        ->getStateUsing(static::getExpiryStatus(...)),

      Tables\Columns\TextColumn::make('expires_at')
        ->label('Berlaku Hingga')
        ->getStateUsing(function ($record) {
          if (!$record || !$record->expires_at) {
            return '-';
          }
          return $record->expires_at->format('d M Y');
        })
        ->sortable()
        ->toggleable(isToggledHiddenByDefault: true),
      Tables\Columns\IconColumn::make('is_active')
        ->label('Aktif')
        ->boolean()
        ->sortable()
        ->visible(static::shouldShowActiveColumn(...)),
      Tables\Columns\TextColumn::make('created_at')
        ->label('Dibuat Pada')
        ->getStateUsing(function ($record) {
          if (!$record || !$record->created_at) {
            return '-';
          }
          return $record->created_at->format('d M Y');
        })
        ->sortable()
        ->toggleable(isToggledHiddenByDefault: true),
    ];
  }

  protected static function getTableHeaderActions(): array
  {
    return [
      // Import Action - Fixed
      Tables\Actions\Action::make('import_users')
        ->label('Import User & Profile')
        ->icon('heroicon-o-arrow-up-tray')
        ->color('success')
        ->form([
          Forms\Components\FileUpload::make('file')
            ->label('Upload File')
            ->acceptedFileTypes(['application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'text/csv'])
            ->required()
            ->helperText('Format yang didukung: Excel (.xlsx, .xls) dan CSV (.csv)')
            ->disk('public')
            ->directory('imports')
            ->storeFileNamesIn('original_filename'),

          Forms\Components\Toggle::make('auto_assign_role')
            ->label('Auto Assign Role Siswa')
            ->default(true)
            ->helperText('Semua user yang diimport akan otomatis diberi role siswa.'),
        ])
        ->action(function (array $data) {
          try {
            // Handle both relative path (public disk) and absolute path
            if (str_starts_with($data['file'], 'imports/')) {
              $filePath = public_path('storage/' . $data['file']);
            } else {
              $filePath = storage_path('app/public/' . $data['file']);
            }

            if (!file_exists($filePath)) {
              throw new \Exception('File tidak ditemukan di: ' . $filePath);
            }

            // Process file based on extension
            $extension = pathinfo($filePath, PATHINFO_EXTENSION);

            if (in_array($extension, ['xlsx', 'xls'])) {
              $results = static::processExcelFile($filePath, $data['auto_assign_role']);
            } elseif ($extension === 'csv') {
              $results = static::processCsvFile($filePath, $data['auto_assign_role']);
            } else {
              throw new \Exception('Format file tidak didukung.');
            }

            // Clean up
            unlink($filePath);

            Notification::make()
              ->title('Import Berhasil')
              ->body("{$results['success']} user berhasil diimport. {$results['failed']} gagal.")
              ->success()
              ->send();
          } catch (\Exception $e) {
            Notification::make()
              ->title('Import Gagal')
              ->body('Error: ' . $e->getMessage())
              ->danger()
              ->send();
          }
        }),

      Tables\Actions\ActionGroup::make([
        Tables\Actions\Action::make('download_excel')
          ->label('📊 Excel Template')
          ->icon('heroicon-o-document-text')
          ->color('success')
          ->button()
          ->action(function () {
            return response()->download(
              storage_path('app/templates/user_import_template.xlsx'),
              'Template_Import_User_Excel.xlsx'
            );
          }),

        Tables\Actions\Action::make('download_csv')
          ->label('📋 CSV Template')
          ->icon('heroicon-o-document')
          ->color('info')
          ->button()
          ->action(function () {
            return response()->download(
              storage_path('app/templates/user_import_template.csv'),
              'Template_Import_User_CSV.csv'
            );
          }),
      ])
        ->label('Download Template')
        ->icon('heroicon-o-document-arrow-down')
        ->color('info')
        ->button(),

      ExportAction::make()
        ->label('Export Semua User'),

      Tables\Actions\Action::make('check_expired')
        ->label('Cek User Kadaluarsa')
        ->icon('heroicon-o-clock')
        ->color('warning')
        ->action(function () {
          $count = User::checkAndDeactivateExpiredUsers();

          Notification::make()
            ->title('Pemeriksaan User Kadaluarsa')
            ->body("$count user kadaluarsa telah dinonaktifkan.")
            ->success()
            ->send();
        }),
    ];
  }

  protected static function getTableActions(): array
  {
    return [
      Tables\Actions\EditAction::make(),

      Tables\Actions\Action::make('print_library_card')
        ->label('Cetak Kartu')
        ->icon('heroicon-o-identification')
        ->color('indigo')
        ->visible(function (?User $record) {
          if (!$record) return false;
          try {
            return $record->canPrintLibraryCard();
          } catch (\Exception $e) {
            return false;
          }
        })
        ->url(fn(User $record): string => route('library-card.print-single', $record))
        ->openUrlInNewTab(),

      Tables\Actions\Action::make('activate_account')
        ->label('Aktivasi')
        ->icon('heroicon-o-check-circle')
        ->color('success')
        ->visible(function (?User $record) {
          if (!$record) return false;
          try {
            return $record->isStudent() && !$record->is_active;
          } catch (\Exception $e) {
            return false;
          }
        })
        ->form([
          Forms\Components\Select::make('years')
            ->label('Masa Berlaku')
            ->options([
              1 => '1 Tahun',
              2 => '2 Tahun',
              3 => '3 Tahun',
            ])
            ->default(3)
            ->required(),
        ])
        ->action(function (User $record, array $data) {
          $record->update([
            'is_active' => true,
            'activated_at' => now(),
            'expires_at' => now()->addYears($data['years']),
          ]);

          Notification::make()
            ->title('Akun Diaktivasi')
            ->body("Akun {$record->name} telah diaktivasi untuk {$data['years']} tahun.")
            ->success()
            ->send();
        }),

      Tables\Actions\Action::make('extend_account')
        ->label('Perpanjang')
        ->icon('heroicon-o-calendar-days')
        ->color('info')
        ->visible(function (?User $record) {
          if (!$record) return false;
          try {
            return $record->isStudent() && $record->is_active;
          } catch (\Exception $e) {
            return false;
          }
        })
        ->form([
          Forms\Components\Select::make('years')
            ->label('Perpanjang')
            ->options([
              1 => '1 Tahun',
              2 => '2 Tahun',
              3 => '3 Tahun',
            ])
            ->default(3)
            ->required(),
        ])
        ->action(function (User $record, array $data) {
          $record->extendAccount($data['years']);

          Notification::make()
            ->title('Masa Berlaku Diperpanjang')
            ->body("Akun {$record->name} diperpanjang {$data['years']} tahun.")
            ->success()
            ->send();
        }),


      Tables\Actions\DeleteAction::make(),
    ];
  }

  protected static function getTableBulkActions(): array
  {
    return [
      ExportBulkAction::make()
        ->label('Export yang Dipilih'),

      // Bulk action untuk cetak kartu
      Tables\Actions\BulkAction::make('bulk_print_cards')
        ->label('Cetak Kartu Terpilih')
        ->icon('heroicon-o-identification')
        ->color('indigo')
        ->requiresConfirmation()
        ->modalHeading('Cetak Kartu Perpustakaan')
        ->modalDescription('Kartu akan dicetak untuk semua siswa aktif yang dipilih.')
        ->action(function ($records) {
          $activeStudentIds = [];

          foreach ($records as $record) {
            if (!$record) continue;

            try {
              if ($record->canPrintLibraryCard()) {
                $activeStudentIds[] = $record->id;
              }
            } catch (\Exception $e) {
              continue;
            }
          }

          if (empty($activeStudentIds)) {
            Notification::make()
              ->title('Tidak Ada Kartu yang Dapat Dicetak')
              ->body('Tidak ada siswa aktif yang dipilih.')
              ->warning()
              ->send();
            return;
          }

          // Redirect ke bulk print
          $url = route('library-card.print-bulk', ['user_ids' => $activeStudentIds]);

          Notification::make()
            ->title('Membuka Cetak Kartu')
            ->body(count($activeStudentIds) . ' kartu akan dicetak.')
            ->success()
            ->send();

          // Return redirect untuk membuka di tab baru
          return redirect($url);
        }),

      Tables\Actions\BulkAction::make('smart_action')
        ->label('Kelola Siswa Terpilih')
        ->icon('heroicon-o-academic-cap')
        ->color('success')
        ->requiresConfirmation()
        ->modalHeading(fn($records) => static::getSmartActionHeading($records))
        ->modalDescription(fn($records) => static::getSmartActionDescription($records))
        ->form(fn($records) => static::getSmartActionForm($records))
        ->action(function ($records, array $data) {
          return static::executeSmartAction($records, $data);
        }),

      Tables\Actions\BulkAction::make('assign_role')
        ->label('Berikan Role')
        ->icon('heroicon-o-user-plus')
        ->color('warning')
        ->requiresConfirmation()
        ->modalHeading('Berikan Role ke User Terpilih')
        ->modalDescription('Pilih role yang akan diberikan ke semua user yang dipilih.')
        ->form([
          Forms\Components\Select::make('role')
            ->label('Role')
            ->options(Role::pluck('name', 'name')->toArray())
            ->required()
            ->default(RoleEnum::SISWA->value)
            ->helperText('Role yang dipilih akan diberikan ke semua user yang dipilih.'),

          Forms\Components\Toggle::make('activate_students')
            ->label('Aktivasi Otomatis untuk Siswa')
            ->default(true)
            ->helperText('Jika role yang dipilih adalah siswa, user akan otomatis diaktivasi.')
            ->visible(fn(Forms\Get $get) => $get('role') === RoleEnum::SISWA->value),

          Forms\Components\Select::make('years')
            ->label('Masa Berlaku (untuk Siswa)')
            ->options([
              1 => '1 Tahun',
              2 => '2 Tahun',
              3 => '3 Tahun',
            ])
            ->default(3)
            ->visible(fn(Forms\Get $get) => $get('role') === RoleEnum::SISWA->value)
            ->required(fn(Forms\Get $get) => $get('role') === RoleEnum::SISWA->value),
        ])
        ->action(function ($records, array $data) {
          $assignedCount = 0;
          $activatedCount = 0;

          foreach ($records as $record) {
            if (!$record) continue;

            try {
              // Assign role
              $record->syncRoles([$data['role']]);
              $assignedCount++;

              if ($data['role'] === RoleEnum::SISWA->value && ($data['activate_students'] ?? false)) {
                $record->update([
                  'is_active' => true,
                  'activated_at' => now(),
                  'expires_at' => now()->addYears($data['years']),
                ]);
                $activatedCount++;
              }

              // Automatically activate admin/petugas
              if (in_array($data['role'], [RoleEnum::ADMIN->value, RoleEnum::PETUGAS->value])) {
                $record->update([
                  'is_active' => true,
                  'activated_at' => now(),
                  'expires_at' => null,
                ]);
                $activatedCount++;
              }
            } catch (\Exception $e) {
              continue;
            }
          }

          $message = "$assignedCount user berhasil diberi role '{$data['role']}'.";
          if ($activatedCount > 0) {
            $message .= " $activatedCount user telah diaktivasi.";
          }

          Notification::make()
            ->title('Assign Role Berhasil')
            ->body($message)
            ->success()
            ->send();
        }),

      Tables\Actions\BulkActionGroup::make([
        Tables\Actions\DeleteBulkAction::make(),
      ]),
    ];
  }

  protected static function getSmartActionHeading($records): string
  {
    $analysis = static::analyzeRecords($records);

    if ($analysis['all_inactive_students']) {
      return 'Aktivasi Akun Siswa';
    } elseif ($analysis['all_active_students']) {
      return 'Perpanjang Masa Berlaku Siswa';
    } else {
      return 'Kelola Akun Siswa';
    }
  }

  protected static function getSmartActionDescription($records): string
  {
    $analysis = static::analyzeRecords($records);

    if ($analysis['all_inactive_students']) {
      return 'Semua siswa yang dipilih akan diaktivasi dan diberi masa berlaku sesuai pilihan.';
    } elseif ($analysis['all_active_students']) {
      return 'Masa berlaku akun semua siswa yang dipilih akan diperpanjang sesuai pilihan.';
    } else {
      return 'Sistem akan memproses setiap akun sesuai dengan statusnya masing-masing.';
    }
  }

  protected static function getSmartActionForm($records): array
  {
    $analysis = static::analyzeRecords($records);

    $form = [
      Forms\Components\Select::make('years')
        ->label($analysis['all_inactive_students'] ? 'Masa Berlaku Akun' : ($analysis['all_active_students'] ? 'Perpanjang Masa Berlaku' : 'Masa Berlaku'))
        ->options([
          1 => '1 Tahun',
          2 => '2 Tahun',
          3 => '3 Tahun',
        ])
        ->default(3)
        ->required()
        ->helperText('Pilih berapa lama akun siswa akan berlaku.'),
    ];

    if (!$analysis['all_inactive_students'] && !$analysis['all_active_students']) {
      $form[] = Forms\Components\Placeholder::make('info')
        ->content('📋 Detail yang akan diproses:' . PHP_EOL .
          '• Siswa belum aktif: ' . $analysis['inactive_count'] . ' akan diaktivasi' . PHP_EOL .
          '• Siswa sudah aktif: ' . $analysis['active_count'] . ' akan diperpanjang' . PHP_EOL .
          '• Lainnya: ' . $analysis['other_count'] . ' akan dilewati')
        ->extraAttributes(['class' => 'text-sm text-gray-600 whitespace-pre-line']);
    }

    return $form;
  }

  protected static function executeSmartAction($records, array $data)
  {
    $activated = 0;
    $extended = 0;
    $skipped = 0;

    foreach ($records as $record) {
      if (!$record) continue;

      try {
        if ($record->isStudent() && !$record->is_active) {
          $record->update([
            'is_active' => true,
            'activated_at' => now(),
            'expires_at' => now()->addYears($data['years']),
          ]);
          $activated++;
        } elseif ($record->isStudent() && $record->is_active) {
          $record->extendAccount($data['years']);
          $extended++;
        } else {
          $skipped++;
        }
      } catch (\Exception $e) {
        $skipped++;
        continue;
      }
    }

    $messages = [];
    if ($activated > 0) {
      $messages[] = "$activated akun siswa berhasil diaktivasi";
    }
    if ($extended > 0) {
      $messages[] = "$extended akun siswa berhasil diperpanjang";
    }
    if ($skipped > 0) {
      $messages[] = "$skipped record dilewati";
    }

    $title = 'Pemrosesan Akun Selesai';
    $body = implode(', ', $messages) . " untuk masa berlaku {$data['years']} tahun.";

    Notification::make()
      ->title($title)
      ->body($body)
      ->success()
      ->send();
  }

  protected static function analyzeRecords($records): array
  {
    $inactive_students = 0;
    $active_students = 0;
    $others = 0;

    foreach ($records as $record) {
      if (!$record) continue;

      try {
        if ($record->isStudent()) {
          if ($record->is_active) {
            $active_students++;
          } else {
            $inactive_students++;
          }
        } else {
          $others++;
        }
      } catch (\Exception $e) {
        $others++;
      }
    }

    return [
      'all_inactive_students' => $inactive_students > 0 && $active_students == 0 && $others == 0,
      'all_active_students' => $active_students > 0 && $inactive_students == 0 && $others == 0,
      'inactive_count' => $inactive_students,
      'active_count' => $active_students,
      'other_count' => $others,
    ];
  }

  protected static function getExpiryStatus($record): string
  {
    if (!$record) return '-';

    try {
      return $record->expiry_status;
    } catch (\Exception $e) {
      return 'Error';
    }
  }

  protected static function getExpiryStatusColor($record): string
  {
    if (!$record) return 'gray';

    try {
      return $record->expiry_status_color;
    } catch (\Exception $e) {
      return 'gray';
    }
  }

  protected static function shouldShowActiveColumn($record): bool
  {
    if (!$record) return false;

    try {
      return !$record->hasRole([RoleEnum::ADMIN->value, RoleEnum::PETUGAS->value]);
    } catch (\Exception $e) {
      return true;
    }
  }

  protected static function getTableFilters(): array
  {
    return [
      SelectFilter::make('role_name')
        ->label('Role')
        ->relationship('roles', 'name')
        ->multiple()
        ->preload(),

      Tables\Filters\Filter::make('active_users')
        ->label('User Aktif')
        ->query(
          fn(Builder $query) => $query->where('users.is_active', true)
            ->orWhereHas('roles', function ($q) {
              $q->whereIn('name', [RoleEnum::ADMIN->value, RoleEnum::PETUGAS->value]);
            })
        ),

      Tables\Filters\Filter::make('inactive_students')
        ->label('Siswa Belum Aktif')
        ->query(
          fn(Builder $query) => $query->where('users.is_active', false)
            ->whereHas('roles', function ($q) {
              $q->where('name', RoleEnum::SISWA->value);
            })
        ),

      Tables\Filters\Filter::make('expired_users')
        ->label('Siswa Kadaluarsa')
        ->query(
          fn(Builder $query) => $query->where('users.expires_at', '<=', now())
            ->whereHas('roles', function ($q) {
              $q->where('name', RoleEnum::SISWA->value);
            })
        ),

      Tables\Filters\Filter::make('expiring_soon')
        ->label('Akan Kadaluarsa (30 hari)')
        ->query(
          fn(Builder $query) => $query->whereBetween('users.expires_at', [now(), now()->addDays(30)])
            ->whereHas('roles', function ($q) {
              $q->where('name', RoleEnum::SISWA->value);
            })
        ),
    ];
  }

  public static function getRelations(): array
  {
    return [];
  }

  public static function getPages(): array
  {
    return [
      'index' => Pages\ListUsers::route('/'),
      'create' => Pages\CreateUser::route('/create'),
      'edit' => Pages\EditUser::route('/{record}/edit'),
    ];
  }

  public static function canViewAny(): bool
  {
    return Auth::check() && Auth::user()->hasRole(RoleEnum::ADMIN->value);
  }

  public static function getExportColumns(): array
  {
    return [
      'name' => 'Nama',
      'email' => 'Email',
      'role_name' => 'Role',
      'is_active' => 'Aktif',
      'activated_at' => 'Diaktivasi',
      'expires_at' => 'Berlaku Hingga',
      'profile_nis' => 'NIS',
      'profile_nisn' => 'NISN',
      'profile_class' => 'Kelas',
      'profile_address' => 'Alamat',
      'profile_phone' => 'No HP',
    ];
  }

  public static function getNavigationBadge(): ?string
  {
    try {
      $expiring = User::where('is_active', true)
        ->whereBetween('expires_at', [now(), now()->addDays(30)])
        ->whereHas('roles', function ($q) {
          $q->where('name', RoleEnum::SISWA->value);
        })
        ->count();

      $inactive = User::where('is_active', false)
        ->whereHas('roles', function ($q) {
          $q->where('name', RoleEnum::SISWA->value);
        })
        ->count();

      $total = $expiring + $inactive;

      return $total > 0 ? $total : null;
    } catch (\Exception $e) {
      return null;
    }
  }

  protected static function shouldShowBulkActivate($livewire): bool
  {
    $selectedRecords = $livewire->getSelectedTableRecords();

    if (empty($selectedRecords)) {
      return false;
    }

    foreach ($selectedRecords as $record) {
      if (!$record) continue;

      // Check if user has siswa role AND is not active
      if ($record->hasRole(RoleEnum::SISWA->value) && !$record->is_active) {
        return true;
      }
    }

    return false;
  }

  protected static function processExcelFile($filePath, $autoAssignRole = true): array
  {
    $reader = IOFactory::createReader('Xlsx');
    $spreadsheet = $reader->load($filePath);
    $worksheet = $spreadsheet->getActiveSheet();
    $rows = $worksheet->toArray();

    // Remove header row
    array_shift($rows);

    $success = 0;
    $failed = 0;

    foreach ($rows as $row) {
      if (empty(array_filter($row))) continue; // Skip empty rows

      try {
        $userData = [
          'name' => $row[0] ?? '',
          'email' => $row[1] ?? '',
          'password' => $row[2] ?? 'password',
          'nis' => $row[3] ?? '',
          'nisn' => $row[4] ?? '',
          'class' => $row[5] ?? '',
          'gender' => $row[6] ?? '',
          'address' => $row[7] ?? '',
          'phone' => $row[8] ?? '',
        ];

        if (static::createUserWithProfile($userData, $autoAssignRole)) {
          $success++;
        } else {
          $failed++;
        }
      } catch (\Exception $e) {
        $failed++;
      }
    }

    return ['success' => $success, 'failed' => $failed];
  }

  protected static function processCsvFile($filePath, $autoAssignRole = true): array
  {
    $handle = fopen($filePath, 'r');
    $header = fgetcsv($handle); // Skip header

    $success = 0;
    $failed = 0;

    while (($row = fgetcsv($handle)) !== false) {
      if (empty(array_filter($row))) continue; // Skip empty rows

      try {
        $userData = [
          'name' => $row[0] ?? '',
          'email' => $row[1] ?? '',
          'password' => $row[2] ?? 'password',
          'nis' => $row[3] ?? '',
          'nisn' => $row[4] ?? '',
          'class' => $row[5] ?? '',
          'gender' => $row[6] ?? '',
          'address' => $row[7] ?? '',
          'phone' => $row[8] ?? '',
        ];

        if (static::createUserWithProfile($userData, $autoAssignRole)) {
          $success++;
        } else {
          $failed++;
        }
      } catch (\Exception $e) {
        $failed++;
      }
    }

    fclose($handle);
    return ['success' => $success, 'failed' => $failed];
  }

  protected static function createUserWithProfile($data, $autoAssignRole = true): bool
  {
    try {
      // Validate required fields
      if (empty($data['name']) || empty($data['email'])) {
        return false;
      }

      // Check if user already exists
      if (User::where('email', $data['email'])->exists()) {
        return false;
      }

      DB::beginTransaction();

      // Create user
      $user = User::create([
        'name' => $data['name'],
        'email' => $data['email'],
        'password' => Hash::make($data['password']),
        'is_active' => 1, // Start as active
        'activated_at' => null,
        'expires_at' => null,
      ]);

      // Create profile if data exists
      if (!empty($data['phone']) || !empty($data['address']) || !empty($data['class'])) {
        $profileData = [
          'user_id' => $user->id,
          'nis' => $data['nis'] ?? null,
          'nisn' => $data['nisn'] ?? null,
          'class' => $data['class'] ?? null,
          'gender' => $data['gender'] ?? null,
          'address' => $data['address'] ?? null,
          'phone' => $data['phone'] ?? null,
        ];

        Profile::create($profileData);
      }

      // Assign role
      if ($autoAssignRole) {
        $role = $autoAssignRole ? RoleEnum::SISWA->value : RoleEnum::PETUGAS->value;
        if (Role::where('name', $role)->exists()) {
          $user->assignRole($role);
        } else {
          $user->assignRole('siswa'); // Default role
        }
      }

      DB::commit();
      return true;
    } catch (\Exception $e) {
      DB::rollback();
      return false;
    }
  }

  protected static function shouldShowBulkExtend($livewire): bool
  {
    $selectedRecords = $livewire->getSelectedTableRecords();

    if (empty($selectedRecords)) {
      return false;
    }

    foreach ($selectedRecords as $record) {
      if (!$record) continue;

      try {
        if ($record->hasRole(RoleEnum::SISWA->value) && $record->is_active) {
          return true;
        }
      } catch (\Exception $e) {
        continue;
      }
    }

    return false;
  }
}
