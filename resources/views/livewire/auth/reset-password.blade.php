<div class="min-h-screen flex items-center justify-center bg-gray-100 px-4">
  <div class="w-full max-w-md bg-white p-8 rounded-2xl shadow-xl space-y-6">
    <div class="text-center">
      <h2 class="text-2xl font-bold text-gray-800">Atur Ulang Password</h2>
      <p class="text-sm text-gray-500">Masukkan password baru kamu di bawah ini</p>
    </div>

    <form wire:submit.prevent="resetPassword" class="space-y-4">
      <div>
        <label for="email" class="block mb-1 text-sm font-medium text-gray-700">Email</label>
        <input wire:model.defer="email" id="email" type="email" required
               class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
        @error('email') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
      </div>

      <div>
        <label for="password" class="block mb-1 text-sm font-medium text-gray-700">Password Baru</label>
        <input wire:model.defer="password" id="password" type="password" required
               class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
        @error('password') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
      </div>

      <div>
        <label for="password_confirmation" class="block mb-1 text-sm font-medium text-gray-700">Konfirmasi Password</label>
        <input wire:model.defer="password_confirmation" id="password_confirmation" type="password" required
               class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
      </div>

      <button type="submit"
              class="inline-flex justify-center items-center gap-x-2 w-full bg-blue-600 text-white text-sm font-medium rounded-xl py-3 transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
        <span wire:loading class="animate-spin inline-block h-4 w-4 border-2 border-white border-t-transparent rounded-full"></span>
        Reset Password
      </button>
    </form>

    <div class="text-center text-sm text-gray-600">
      <a href="{{ route('login') }}" class="text-blue-600 font-medium hover:underline" wire:navigate>Kembali ke login</a>
    </div>
  </div>
</div>
