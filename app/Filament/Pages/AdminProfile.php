<?php

namespace App\Filament\Pages;

use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminProfile extends Page implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    protected static ?string $navigationLabel = 'Profil';

    protected static string $view = 'filament.pages.admin-profile';

    public $username;

    public $name;

    public $nis;

    public $email;

    public $phone;

    public $address;

    public $gender;

    public $birth_date;

    public $profile_photo_path;

    public $current_password;

    public $new_password;

    public $new_password_confirmation;

    public $change_password = false; // Toggle untuk mengubah password

    public function mount(): void
    {
        $user = Auth::user();
        $this->form->fill([
            'username' => $user->username,
            'name' => $user->name,
            'nis' => $user->nis,
            'email' => $user->email,
            'phone' => $user->phone,
            'address' => $user->address,
            'gender' => $user->gender,
            'birth_date' => $user->birth_date,
            'profile_photo_path' => $user->profile_photo_path,
        ]);
    }

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\FileUpload::make('profile_photo_path')
                ->label('Foto Profil')
                ->directory('profile-photos')
                ->image()
                ->imageEditor()
                ->imageEditorAspectRatios(['1:1'])
                ->imagePreviewHeight('150')
                ->imageResizeMode('cover')
                ->maxSize(2048)
                ->acceptedFileTypes(['image/jpeg', 'image/jpg', 'image/png'])
                ->avatar()
                ->hint('Format JPG, PNG. Maks 2MB')
                ->columnSpanFull(),

            Forms\Components\Grid::make(2)
                ->schema([
                    Forms\Components\TextInput::make('username')
                        ->label('Username')
                        ->disabled(),

                    Forms\Components\TextInput::make('name')
                        ->label('Nama Lengkap')
                        ->required(),

                    Forms\Components\TextInput::make('nis')
                        ->label('NIS/NISN')
                        ->disabled(),

                    Forms\Components\TextInput::make('email')
                        ->label('Email')
                        ->email()
                        ->required(),

                    Forms\Components\TextInput::make('phone')
                        ->label('No. Telp')
                        ->tel(),

                    Forms\Components\TextInput::make('address')
                        ->label('Alamat'),

                    Forms\Components\Select::make('gender')
                        ->label('Gender')
                        ->options([
                            'Male' => 'Male',
                            'Female' => 'Female',
                        ]),

                    Forms\Components\DatePicker::make('birth_date')
                        ->label('Tanggal Lahir'),
                ]),

            Forms\Components\Section::make('Pengaturan Password')
                ->schema([
                    Forms\Components\Toggle::make('change_password')
                        ->label('Ubah Password')
                        ->helperText('Aktifkan jika ingin mengubah password')
                        ->reactive()
                        ->afterStateUpdated(function ($state) {
                            if (! $state) {
                                // Reset password fields when toggle is turned off
                                $this->current_password = null;
                                $this->new_password = null;
                                $this->new_password_confirmation = null;
                            }
                        }),

                    Forms\Components\TextInput::make('current_password')
                        ->label('Password Lama')
                        ->password()
                        ->required(fn ($get) => $get('change_password'))
                        ->visible(fn ($get) => $get('change_password'))
                        ->rules(['required_if:change_password,true']),

                    Forms\Components\TextInput::make('new_password')
                        ->label('Password Baru')
                        ->password()
                        ->required(fn ($get) => $get('change_password'))
                        ->visible(fn ($get) => $get('change_password'))
                        ->minLength(8)
                        ->rules(['required_if:change_password,true', 'min:8']),

                    Forms\Components\TextInput::make('new_password_confirmation')
                        ->label('Konfirmasi Password Baru')
                        ->password()
                        ->required(fn ($get) => $get('change_password'))
                        ->visible(fn ($get) => $get('change_password'))
                        ->same('new_password')
                        ->rules(['required_if:change_password,true', 'same:new_password']),
                ])
                ->columns(1)
                ->collapsible(),
        ];
    }

    public function save()
    {
        $user = Auth::user();
        $data = $this->form->getState();

        // Validasi khusus untuk password jika toggle diaktifkan
        if ($data['change_password']) {
            // Validasi Password Lama
            if (empty($data['current_password'])) {
                Notification::make()
                    ->title('Password lama harus diisi')
                    ->danger()
                    ->send();

                return;
            }

            if (! Hash::check($data['current_password'], $user->password)) {
                Notification::make()
                    ->title('Password lama salah')
                    ->danger()
                    ->send();

                return;
            }

            // Validasi Password Baru
            if (empty($data['new_password'])) {
                Notification::make()
                    ->title('Password baru harus diisi')
                    ->danger()
                    ->send();

                return;
            }

            if ($data['new_password'] !== $data['new_password_confirmation']) {
                Notification::make()
                    ->title('Konfirmasi password tidak cocok')
                    ->danger()
                    ->send();

                return;
            }

            // Update Password
            $user->password = Hash::make($data['new_password']);
        }

        // Remove password fields dan toggle dari data sebelum update profil
        unset($data['current_password'], $data['new_password'], $data['new_password_confirmation'], $data['change_password']);

        // Update profil
        $user->update(array_filter($data));

        // Reset toggle password
        $this->change_password = false;
        $this->current_password = null;
        $this->new_password = null;
        $this->new_password_confirmation = null;

        // Success notification
        Notification::make()
            ->title('Profil berhasil diperbarui')
            ->success()
            ->send();
    }

    protected function getFormActions(): array
    {
        return [
            Forms\Components\Actions\Action::make('save')
                ->label('Simpan Perubahan')
                ->submit('save')
                ->color('primary'),
        ];
    }
}
