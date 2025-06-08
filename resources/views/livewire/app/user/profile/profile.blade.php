<div class="mx-auto mb-12 w-10/12">
  <h2 class="text-2xl font-bold mb-6">Profil Siswa</h2>

  @if (session()->has('success'))
    <div class="p-3 mb-4 bg-green-100 text-green-700 border border-green-400 rounded">
      {{ session('success') }}
    </div>
  @endif

  {{-- Profile Form --}}
  <form wire:submit.prevent="updateProfile" class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-10">
    <div>
      <label class="block font-medium">Nama</label>
      <input wire:model.defer="name" type="text" class="w-full border rounded p-2">
      @error('name') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
    </div>

    <div>
      <label class="block font-medium">NIS</label>
      <input wire:model.defer="nis" type="text" class="w-full border rounded p-2">
    </div>

    <div>
      <label class="block font-medium">NISN</label>
      <input wire:model.defer="nisn" type="text" class="w-full border rounded p-2">
    </div>

    <div>
      <label class="block font-medium">Kelas</label>
      <input wire:model.defer="class" type="text" class="w-full border rounded p-2">
    </div>

    <div>
      <label class="block font-medium">Telepon</label>
      <input wire:model.defer="phone" type="text" class="w-full border rounded p-2">
    </div>

    <div class="md:col-span-2">
      <label class="block font-medium">Alamat</label>
      <textarea wire:model.defer="address" class="w-full border rounded p-2" rows="3"></textarea>
    </div>

    <div class="md:col-span-2">
      <label class="block font-medium">Kartu Perpustakaan</label>
      <input wire:model="library_card_image" type="file" class="w-full border rounded p-2">
      @if ($old_image)
        <div class="mt-2">
          <img src="{{ asset('storage/' . $old_image) }}" class="w-32 rounded shadow" alt="Kartu Perpustakaan">
        </div>
      @endif
    </div>

    <div class="md:col-span-2 text-right">
      <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Simpan Profil</button>
    </div>
  </form>

  {{-- Password Form --}}
  <h3 class="text-xl font-bold mb-4">Ganti Password</h3>
  <form wire:submit.prevent="updatePassword" class="space-y-4 max-w-md">
    <div>
      <label class="block font-medium">Password Lama</label>
      <input wire:model.defer="current_password" type="password" class="w-full border rounded p-2">
      @error('current_password') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
    </div>

    <div>
      <label class="block font-medium">Password Baru</label>
      <input wire:model.defer="new_password" type="password" class="w-full border rounded p-2">
    </div>

    <div>
      <label class="block font-medium">Konfirmasi Password Baru</label>
      <input wire:model.defer="new_password_confirmation" type="password" class="w-full border rounded p-2">
    </div>

    <div>
      <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Ganti Password</button>
    </div>
  </form>
</div>
