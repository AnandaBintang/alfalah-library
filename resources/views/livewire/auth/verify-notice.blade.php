<div class="min-h-screen bg-gray-50 flex items-center justify-center px-4">
  <div class="max-w-md w-full bg-white border border-gray-200 rounded-2xl shadow p-6">
    <div class="flex flex-col items-center text-center">
      <div class="size-14 flex items-center justify-center rounded-full bg-blue-100 text-blue-600">
        <svg xmlns="http://www.w3.org/2000/svg" class="size-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                d="m21.75 6.75-9.75 7.5-9.75-7.5m19.5 10.5V6.75A2.25 2.25 0 0 0 19.5 4.5h-15A2.25 2.25 0 0 0 2.25 6.75v10.5A2.25 2.25 0 0 0 4.5 19.5h15a2.25 2.25 0 0 0 2.25-2.25Z"/>
        </svg>
      </div>

      <h2 class="mt-4 text-xl font-semibold text-gray-900">Verifikasi Email Kamu</h2>
      <p class="mt-2 text-gray-600 text-sm">
        Kami sudah mengirimkan tautan verifikasi ke email kamu.
        Silakan cek inbox atau folder spam.
      </p>

      {{-- Pesan jika sudah kirim ulang --}}
      @if (session('message'))
        <div class="mt-4 w-full text-sm text-green-700 bg-green-100 border border-green-200 rounded-lg p-3">
          {{ session('message') }}
        </div>
      @endif

      <div class="mt-6 w-full space-y-3">
        {{-- Kirim ulang email --}}
        <form wire:submit.prevent="resendVerification">
          <button type="submit"
                  class="w-full inline-flex justify-center items-center rounded-lg bg-blue-600 text-white px-4 py-2 text-sm font-medium hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
            Kirim Ulang Email Verifikasi
          </button>
        </form>

        {{-- Logout --}}
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit"
                  class="w-full inline-flex justify-center items-center rounded-lg bg-gray-100 text-gray-800 px-4 py-2 text-sm font-medium hover:bg-gray-200">
            Logout
          </button>
        </form>
      </div>
    </div>
  </div>
</div>
