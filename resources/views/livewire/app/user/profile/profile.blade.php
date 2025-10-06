<div class="mx-auto mb-12 w-10/12 border border-gray-100 p-5 rounded-lg shadow-lg">
  <h2 class="text-2xl font-bold mb-6 ">Profil Siswa</h2>


  @if (session()->has('success'))
    <div class="p-3 mb-4 bg-green-100 text-green-700 border border-green-400 rounded">
      {{ session('success') }}
    </div>
  @endif

  @if (!Auth::user()->hasVerifiedEmail())
    <div class="p-3 mb-4 bg-yellow-100 text-yellow-800 border border-yellow-400 rounded w-fit">
      Email Anda belum diverifikasi.
      <button
        wire:click="sendVerificationEmail"
        class="ml-2 underline text-blue-600 hover:text-blue-800"
      >
        Kirim ulang verifikasi
      </button>
    </div>
  @endif

  {{-- Total Kunjungan Satu Tahun --}}
  <div class="flex flex-col bg-white border border-gray-200 shadow-2xs rounded-xl w-fit">
    <div class="p-4 md:p-5">
      <h3 class="text-lg font-bold text-gray-800">
        Total Kunjungan Satu Tahun
      </h3>
      <p class="mt-1 ">
        {{ $totalKunjunganSatuTahun }}
      </p>
    </div>
  </div>

  {{-- Profile Form --}}
  <form wire:submit.prevent="updateProfile" class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-10">
    <div>
      <label class="block font-medium">Nama</label>
      <input wire:model.defer="name" type="text" class="w-full border rounded p-2">
      @error('name')
      <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
    </div>

    <div>
      <label class="block font-medium">Email</label>
      <input wire:model.defer="email" type="text" class="w-full border rounded p-2" readonly>
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

    {{--    <div class="md:col-span-2">--}}
    {{--      <label class="block font-medium">Kartu Perpustakaan</label>--}}
    {{--      <input wire:model="library_card_image" type="file" class="w-full border rounded p-2">--}}
    {{--      @if ($old_image)--}}
    {{--        <div class="mt-2">--}}
    {{--          <img src="{{ asset('storage/' . $old_image) }}" class="w-32 rounded shadow" alt="Kartu Perpustakaan">--}}
    {{--        </div>--}}
    {{--      @endif--}}
    {{--    </div>--}}

    <div class="md:col-span-2 text-right">
      <button type="button"
              class="py-3 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-yellow-500 text-white hover:bg-yellow-600 focus:outline-hidden focus:bg-yellow-600 disabled:opacity-50 disabled:pointer-events-none"
              aria-haspopup="dialog" aria-expanded="false" aria-controls="hs-scale-animation-modal"
              data-hs-overlay="#hs-scale-animation-modal">
        Ganti Password
      </button>
      <button type="submit" class="bg-blue-600 text-white px-4 py-3 rounded-lg font-medium hover:bg-blue-700">Simpan
        Profil
      </button>
    </div>
  </form>

  <div id="hs-scale-animation-modal"
       class="hs-overlay hidden size-full fixed top-0 start-0 z-80 overflow-x-hidden overflow-y-auto pointer-events-none"
       role="dialog" tabindex="-1" aria-labelledby="hs-scale-animation-modal-label">
    <div
      class="hs-overlay-animation-target hs-overlay-open:scale-100 hs-overlay-open:opacity-100 scale-95 opacity-0 ease-in-out transition-all duration-200 sm:max-w-lg sm:w-full m-3 sm:mx-auto min-h-[calc(100%-56px)] flex items-center">
      <div class="w-full flex flex-col bg-white border border-gray-200 shadow-2xs rounded-xl pointer-events-auto">
        <div class="flex justify-between items-center py-3 px-4 border-b border-gray-200">
          <h3 id="hs-scale-animation-modal-label" class="font-bold text-gray-800">
            Ganti Password
          </h3>
          <button type="button"
                  class="size-8 inline-flex justify-center items-center gap-x-2 rounded-full border border-transparent bg-gray-100 text-gray-800 hover:bg-gray-200 focus:outline-hidden focus:bg-gray-200 disabled:opacity-50 disabled:pointer-events-none"
                  aria-label="Close" data-hs-overlay="#hs-scale-animation-modal">
            <span class="sr-only">Close</span>
            <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M18 6 6 18"></path>
              <path d="m6 6 12 12"></path>
            </svg>
          </button>
        </div>
        <div class="p-4 overflow-y-auto">
          {{-- Form ganti password --}}
          <form wire:submit.prevent="updatePassword" class="space-y-4 max-w-md">
            <div>
              <label class="block font-medium">Password Lama</label>
              <input wire:model.defer="current_password" type="password" class="w-full border rounded p-2">
              @error('current_password')
              <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
              <label class="block font-medium">Password Baru</label>
              <input wire:model.defer="new_password" type="password" class="w-full border rounded p-2">
            </div>

            <div>
              <label class="block font-medium">Konfirmasi Password Baru</label>
              <input wire:model.defer="new_password_confirmation" type="password" class="w-full border rounded p-2">
            </div>

            <div class="text-sm mt-1 text-right">
              <a href="{{ route('password.request') }}" class="text-blue-600 hover:underline" wire:navigate>
                Lupa password lama?
              </a>
            </div>

            <div>
              <button type="button"
                      class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-2xs hover:bg-gray-50 focus:outline-hidden focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none"
                      data-hs-overlay="#hs-scale-animation-modal">
                Close
              </button>
              <button type="submit" class="bg-green-600 text-white px-3 py-2 rounded hover:bg-green-700">Ganti
                Password
              </button>
            </div>
          </form>
        </div>

      </div>
    </div>
  </div>

  {{-- Password Form --}}
  {{--  <h3 class="text-xl font-bold mb-4">Ganti Password</h3>--}}
  {{--  <form wire:submit.prevent="updatePassword" class="space-y-4 max-w-md">--}}
  {{--    <div>--}}
  {{--      <label class="block font-medium">Password Lama</label>--}}
  {{--      <input wire:model.defer="current_password" type="password" class="w-full border rounded p-2">--}}
  {{--      @error('current_password')--}}
  {{--      <span class="text-red-600 text-sm">{{ $message }}</span> @enderror--}}
  {{--    </div>--}}

  {{--    <div>--}}
  {{--      <label class="block font-medium">Password Baru</label>--}}
  {{--      <input wire:model.defer="new_password" type="password" class="w-full border rounded p-2">--}}
  {{--    </div>--}}

  {{--    <div>--}}
  {{--      <label class="block font-medium">Konfirmasi Password Baru</label>--}}
  {{--      <input wire:model.defer="new_password_confirmation" type="password" class="w-full border rounded p-2">--}}
  {{--    </div>--}}

  {{--    <div class="text-sm mt-1 text-right">--}}
  {{--      <a href="{{ route('password.request') }}" class="text-blue-600 hover:underline" wire:navigate>--}}
  {{--        Lupa password lama?--}}
  {{--      </a>--}}
  {{--    </div>--}}

  {{--    <div>--}}
  {{--      <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Ganti Password</button>--}}
  {{--    </div>--}}
  {{--  </form>--}}
</div>
