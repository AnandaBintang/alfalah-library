<?php

namespace App\Livewire\App\User\Profile;

use App\Trait\NotificationsAndDialog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;


#[Title('Profile')]
#[Layout('livewire.layouts.main-app')]
class Profile extends Component
{
  use WithFileUploads, NotificationsAndDialog;

  public $name, $nis, $nisn, $class, $address, $phone;
  public $library_card_image, $old_image;

  public $current_password, $new_password, $new_password_confirmation;

  public function mount()
  {
    $user = auth()->user();
    $this->name = $user->name;

    $this->nis = $user->profile->nis ?? '';
    $this->nisn = $user->profile->nisn ?? '';
    $this->class = $user->profile->class ?? '';
    $this->address = $user->profile->address ?? '';
    $this->phone = $user->profile->phone ?? '';
    $this->old_image = $user->profile->library_card_image_path ?? null;
  }

  public function updateProfile()
  {
    try {
      $this->validate([
        'name' => 'required|string|max:255',
        'nis' => 'nullable|string|max:20',
        'nisn' => 'nullable|string|max:20',
        'class' => 'nullable|string|max:50',
        'address' => 'nullable|string',
        'phone' => 'nullable|string|max:20',
        'library_card_image' => 'nullable|image|max:5120',
      ]);

      $user = auth()->user();
      $user->update(['name' => $this->name]);

      $profileData = [
        'nis' => $this->nis,
        'nisn' => $this->nisn,
        'class' => $this->class,
        'address' => $this->address,
        'phone' => $this->phone,
      ];

      if ($this->library_card_image) {
        $filename = Str::random(30) . '.' . $this->library_card_image->getClientOriginalExtension();
        $path = $this->library_card_image->storeAs('library_card_student', $filename, 'public');
        $profileData['library_card_image_path'] = $path;

        if ($this->old_image) {
          Storage::disk('public')->delete($this->old_image);
        }

        $this->old_image = $path;
      }
      $user->profile()->updateOrCreate(['user_id' => $user->id], $profileData);

      $this->successNotification('Success', 'Profil berhasil diperbarui.');
    } catch (\Throwable $th) {
      $this->errorNotification( $th->getLine(), $th->getMessage());
    }
  }

  public function updatePassword()
  {
    $this->validate([
      'current_password' => 'required',
      'new_password' => ['required', 'confirmed'],
    ]);

    $user = auth()->user();
    if (!Hash::check($this->current_password, $user->password)) {
      $this->addError('current_password', 'Password lama salah.');
      return;
    }

    $user->update(['password' => Hash::make($this->new_password)]);

    $this->reset(['current_password', 'new_password', 'new_password_confirmation']);
    Auth::logout();

    $this->redirect(route('login'));
    $this->successNotification('Success', 'Password berhasil diubah.');
  }

  public function render()
  {
    return view('livewire.app.user.profile.profile');
  }
}
