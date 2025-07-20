<div class="min-h-screen flex items-center justify-center bg-gray-100 px-4">
  <div class="w-full max-w-md bg-white p-8 rounded-2xl shadow-xl space-y-6">
    <div class="text-center">
      <h2 class="text-2xl font-bold text-gray-800">Reset Password</h2>
      <p class="text-sm text-gray-500">Masukkan email kamu untuk mengatur ulang password</p>
    </div>

    <form wire:submit.prevent="submit" class="space-y-4">
      <div>
        <label for="email" class="block mb-1 text-sm font-medium text-gray-700">Email</label>
        <input wire:model.defer="email" type="email" id="email"
               class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
               placeholder="contoh@email.com" required>
        @error('email') <span class="text-sm text-red-600 mt-1 block">{{ $message }}</span> @enderror
      </div>

      <button type="submit"
              class="inline-flex justify-center items-center gap-x-2 w-full bg-blue-600 text-white text-sm font-medium rounded-xl py-3 transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
        <span wire:loading class="animate-spin inline-block h-4 w-4 border-2 border-white border-t-transparent rounded-full"></span>
        Kirim Link Reset
      </button>
    </form>

    <div class="text-center text-sm text-gray-600">
      <a href="{{ route('login') }}" class="text-blue-600 font-medium hover:underline" wire:navigate>Kembali ke login</a>
    </div>
  </div>
</div>
