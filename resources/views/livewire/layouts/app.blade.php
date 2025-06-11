<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" type="image/x-icon" href="{{ asset("logo/logo-alfalah.png") }}">

  <title>{{ $title ?? 'Perpustakaan Al-Falah' }}</title>
  @vite('resources/css/app.css')
  @vite('resources/js/app.js')
  @livewireStyles

  <style>
    .nav-link.active {
      color: #2563eb;
      font-weight: 700;
    }

  </style>
</head>
<body class="flex flex-col min-h-screen">


  {{--    Navbar --}}
  <header class="flex flex-wrap sm:justify-start sm:flex-nowrap w-full bg-white text-sm py-3 mb-10">
    <nav
      class="fixed top-0 left-0 right-0 z-50 bg-white w-full mx-auto px-4 py-4 flex flex-wrap items-center justify-between shadow">
      <div class="flex space-x-1.5">
        <img src="{{ asset("logo/logo-alfalah.png") }}" alt="logo" class="hidden sm:block ">
        <a class="sm:order-1 flex-none text-xl font-semibold focus:outline-hidden focus:opacity-80 "
           href="{{ route('book.index') }}" wire:navigate>SMP
          Alfalah Assalam</a>
      </div>

      <div class="sm:order-3 flex items-center gap-x-2">
        <button type="button"
                class="sm:hidden hs-collapse-toggle relative size-9 flex justify-center items-center gap-x-2 rounded-lg border border-gray-200 bg-white text-gray-800 shadow-2xs hover:bg-gray-50 focus:outline-hidden focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none"
                id="hs-navbar-alignment-collapse" aria-expanded="false" aria-controls="hs-navbar-alignment"
                aria-label="Toggle navigation" data-hs-collapse="#hs-navbar-alignment">
          <svg class="hs-collapse-open:hidden shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
               viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
               stroke-linejoin="round">
            <line x1="3" x2="21" y1="6" y2="6"/>
            <line x1="3" x2="21" y1="12" y2="12"/>
            <line x1="3" x2="21" y1="18" y2="18"/>
          </svg>
          <svg class="hs-collapse-open:block hidden shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24"
               height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
               stroke-linejoin="round">
            <path d="M18 6 6 18"/>
            <path d="m6 6 12 12"/>
          </svg>
          <span class="sr-only">Toggle</span>
        </button>

        @if(!\Illuminate\Support\Facades\Auth::check())
        <a type="button" href="{{ route('login') }}"
                class="hidden md:block py-2 px-3 items-center gap-x-2 text-sm font-medium rounded-lg border border-black bg-white text-black hover:bg-white focus:outline-hidden focus:bg-white focus:outline-hidden focus:bg-white disabled:opacity-50 disabled:pointer-events-none">
          Login
        </a>
        <a type="button" href="{{ route('register') }}"
                class="hidden md:block py-2 px-3 items-center gap-x-2 text-sm font-medium rounded-lg border border-black bg-white text-black hover:bg-white focus:outline-hidden focus:bg-white focus:outline-hidden focus:bg-white disabled:opacity-50 disabled:pointer-events-none">
          Sign Up
        </a>
        @endif
      </div>
      <div id="hs-navbar-alignment"
           class="hs-collapse hidden overflow-hidden transition-all duration-300 basis-full grow sm:grow-0 sm:basis-auto sm:block sm:order-2"
           aria-labelledby="hs-navbar-alignment-collapse">
        <div class="flex flex-col gap-5 mt-5 sm:flex-row sm:items-center sm:mt-0 sm:ps-5">
          @if(!\Illuminate\Support\Facades\Auth::check())
          <a
            class="nav-link font-medium px-2 text-gray-600 hover:text-gray-400 focus:outline-hidden focus:text-gray-400 "
            href="#fitur">Fitur</a>
          <a
            class="nav-link font-medium px-2 text-gray-600 hover:text-gray-400 focus:outline-hidden focus:text-gray-400"
            href="#layanan">Layanan</a>
          <a
            class="nav-link font-medium px-2 text-gray-600 hover:text-gray-400 focus:outline-hidden focus:text-gray-400"
            href="#lokasi">Lokasi</a>
          @endif

          @if(\Illuminate\Support\Facades\Auth::check())
              <a class="font-medium text-gray-600 hover:text-gray-400 focus:outline-hidden focus:text-gray-400"
                 href="{{ route('book.index') }}" >Buku</a>
              <a class="font-medium text-gray-600 hover:text-gray-400 focus:outline-hidden focus:text-gray-400"
                 href="{{ route('donasi.store') }}">Donasi</a>

              <div class="hs-dropdown relative inline-flex">
                <button id="hs-dropdown-default" type="button"
                        class="hs-dropdown-toggle py-3 px-4 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg bg-white text-gray-800 shadow-2xs hover:bg-gray-50 focus:outline-hidden focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-white dark:border-gray-200 dark:text-gray-800 dark:hover:bg-gray-100 dark:focus:bg-gray-100"
                        aria-haspopup="menu" aria-expanded="false" aria-label="Dropdown">
                  Riwayat
                  <svg class="hs-dropdown-open:rotate-180 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                       viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                       stroke-linejoin="round">
                    <path d="m6 9 6 6 6-6"/>
                  </svg>
                </button>

                <div
                  class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden min-w-60 bg-white shadow-md rounded-lg mt-2 dark:bg-white dark:border-gray-200 dark:divide-gray-200 after:h-4 after:absolute after:-bottom-4 after:start-0 after:w-full before:h-4 before:absolute before:-top-4 before:start-0 before:w-full"
                  role="menu" aria-orientation="vertical" aria-labelledby="hs-dropdown-default">
                  <div class="p-1 space-y-0.5">
                    <a
                      class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 dark:text-gray-800 dark:hover:bg-gray-100 dark:focus:bg-gray-100"
                      href="{{ route("donasi.index") }}">
                      Riwayat Donasi
                    </a>
                    <a
                      class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 dark:text-gray-800 dark:hover:bg-gray-100 dark:focus:bg-gray-100"
                      href="{{ route("peminjaman.index") }}">
                      Riwayat Pinjaman
                    </a>
                    <a
                      class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 dark:text-gray-800 dark:hover:bg-gray-100 dark:focus:bg-gray-100"
                      href="{{ route("perpanjang-peminjaman.index") }}">
                      Riwayat Perpanjang Pinjaman
                    </a>
                    <a
                      class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 dark:text-gray-800 dark:hover:bg-gray-100 dark:focus:bg-gray-100"
                      href="{{ route('pengembalian.index') }}">
                      Riwayat Pengembalian
                    </a>
                    <a
                      class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 dark:text-gray-800 dark:hover:bg-gray-100 dark:focus:bg-gray-100"
                      href="{{ route("denda.index") }}">
                      Riwayat Denda
                    </a>
                  </div>
                </div>
          @endif

          @if(!\Illuminate\Support\Facades\Auth::check())
          <div class=" md:hidden lg:hidden grid grid-cols-2 gap-3">
            <a type="button" href="{{ route('login') }}"
               class="py-2 px-3 inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-lg border border-blue-600 text-blue-600 hover:border-blue-500 hover:text-blue-500 focus:outline-hidden focus:border-blue-500 focus:text-blue-500 disabled:opacity-50 disabled:pointer-events-none">
              Login
            </a>
            <a type="button" href="{{ route('register') }}"
               class="py-2 px-3 inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-hidden focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none">
              Sign Up
            </a>
          </div>
          @endif

        </div>
      </div>
    </nav>
  </header>
  {{--    Navbar --}}


  {{--    Main Content --}}
  <div class="flex-grow">
    {{ $slot }}
  </div>
  {{--    Main Content --}}


  {{--    Footer --}}
  <footer class="w-full ">
    <div class="p-5 bg-blue-600 text-sm flex justify-center text-white font-semibold">
      <p>© {{ date('Y') }} SMP Alfalah Assalam. All rights reserved.</p>
    </div>
  </footer>
  {{--    Footer --}}
  <wireui:scripts/>
  <script src="//unpkg.com/alpinejs" defer></script>
  @livewireScripts
</body>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const observer = new IntersectionObserver(entries => {
      entries.forEach(entry => {
        const id = entry.target.getAttribute('id');
        const link = document.querySelector(`a[href="#${id}"]`);
        if (link) {
          if (entry.isIntersecting) {
            document.querySelectorAll('.nav-link').forEach(el => el.classList.remove('active'));
            link.classList.add('active');
          }
        }
      });
    }, {threshold: 0.6});

    document.querySelectorAll('div[id]').forEach(section => {
      observer.observe(section);
    });
  });
</script>
</html>
