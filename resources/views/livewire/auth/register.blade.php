<div class="min-h-screen flex items-center justify-center bg-gray-100 px-4">
  <div class="max-w-md w-full bg-white p-8 rounded shadow">
    <h2 class="text-2xl font-bold mb-6 text-center">Daftar Akun Baru</h2>

    @if($error)
      <div class="mb-4 text-red-600 font-medium">{{ $error }}</div>
    @endif

    @if (session('status'))
      <div class="mb-4 text-green-600 font-medium text-sm text-center">
        {{ session('status') }}
      </div>
    @endif

    <form wire:submit.prevent="register" class="space-y-6">
      <div>
        <label for="name" class="block mb-1 font-medium text-gray-700">Nama Lengkap</label>
        <input wire:model.defer="name" id="name" type="text" required autofocus
               class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
        @error('name') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
      </div>

      <div>
        <label for="email" class="block mb-1 font-medium text-gray-700">Email</label>
        <input wire:model.defer="email" id="email" type="email" required
               class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
        @error('email') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
      </div>

      <div>
        <label for="password" class="block mb-1 font-medium text-gray-700">Password</label>
        <input wire:model.defer="password" id="password" type="password" required
               class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
        @error('password') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
      </div>

      <div>
        <label for="password_confirmation" class="block mb-1 font-medium text-gray-700">Konfirmasi Password</label>
        <input wire:model.defer="password_confirmation" id="password_confirmation" type="password" required
               class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
      </div>

      <button type="submit"
              class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition relative flex justify-center items-center">
        <span wire:loading class="animate-spin inline-block size-4 border-3 border-current border-t-transparent rounded-full mr-1"></span>
        Daftar
      </button>
    </form>

    <p class="mt-4 text-center text-gray-600">
      Sudah punya akun?
      <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Masuk di sini</a>
    </p>
  </div>
</div>
