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
</head>
<body class="flex flex-col min-h-screen">

  {{--  Running Information--}}
  <livewire:components.announcement-banner/>
  {{--  Running Information--}}


  {{--    Navbar --}}
  <header class="flex flex-wrap sm:justify-start sm:flex-nowrap w-full bg-white text-sm py-3 mb-10">
    <nav class="max-w-[85rem] w-full mx-auto px-4 flex flex-wrap basis-full items-center justify-between">
      <div class="flex space-x-1.5">
        <img src="{{ asset("logo/logo-alfalah.png") }}" alt="logo" class="hidden sm:block ">
        <a class="sm:order-1 flex-none text-xl font-semibold focus:outline-hidden focus:opacity-80 "
           href="{{ route('book.index') }}">SMP
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

        {{--Button--}}

        {{--        Notification--}}
        <button type="button"
                class="relative inline-flex justify-center items-center size-11 text-sm font-semibold rounded-lg border border-gray-200 bg-white text-gray-800 shadow-2xs hover:bg-gray-50 focus:outline-hidden focus:bg-gray-50 disabled:opacity-50 cursor-pointer">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 512 512"
               class="">
            <path fill="#000"
                  d="M440.08 341.31c-1.66-2-3.29-4-4.89-5.93c-22-26.61-35.31-42.67-35.31-118c0-39-9.33-71-27.72-95c-13.56-17.73-31.89-31.18-56.05-41.12a3 3 0 0 1-.82-.67C306.6 51.49 282.82 32 256 32s-50.59 19.49-59.28 48.56a3.1 3.1 0 0 1-.81.65c-56.38 23.21-83.78 67.74-83.78 136.14c0 75.36-13.29 91.42-35.31 118c-1.6 1.93-3.23 3.89-4.89 5.93a35.16 35.16 0 0 0-4.65 37.62c6.17 13 19.32 21.07 34.33 21.07H410.5c14.94 0 28-8.06 34.19-21a35.17 35.17 0 0 0-4.61-37.66M256 480a80.06 80.06 0 0 0 70.44-42.13a4 4 0 0 0-3.54-5.87H189.12a4 4 0 0 0-3.55 5.87A80.06 80.06 0 0 0 256 480" />
          </svg>
          <span class="absolute top-0 left-0 inline-flex items-center size-3 -mt-1.5 -ml-1.5">
            <span class="animate-ping absolute inline-flex size-full rounded-full bg-red-400 opacity-75 dark:bg-red-600"></span>
            <span class="relative inline-flex rounded-full size-3 bg-red-500"></span>
          </span>
        </button>



        {{--        Cart--}}
        <a href="{{ route('cart.index') }}"
           class="relative inline-flex justify-center items-center size-11 text-sm font-semibold rounded-lg border border-gray-200 bg-white text-gray-800 shadow-2xs hover:bg-gray-50 focus:outline-hidden focus:bg-gray-50 disabled:opacity-50 ">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
            <path fill="#000"
                  d="M17 18c-1.11 0-2 .89-2 2a2 2 0 0 0 2 2a2 2 0 0 0 2-2a2 2 0 0 0-2-2M1 2v2h2l3.6 7.59l-1.36 2.45c-.15.28-.24.61-.24.96a2 2 0 0 0 2 2h12v-2H7.42a.25.25 0 0 1-.25-.25q0-.075.03-.12L8.1 13h7.45c.75 0 1.41-.42 1.75-1.03l3.58-6.47c.07-.16.12-.33.12-.5a1 1 0 0 0-1-1H5.21l-.94-2M7 18c-1.11 0-2 .89-2 2a2 2 0 0 0 2 2a2 2 0 0 0 2-2a2 2 0 0 0-2-2"/>
          </svg>
          {{--          <span--}}
          {{--            class="absolute top-0 right-0 z-10 inline-flex items-center py-0.5 px-1.5 rounded-full text-xs font-medium transform -translate-y-1/2 translate-x-1/2 bg-red-500 text-white">--}}
          {{--            {{ $cart }}--}}
          {{--          </span>--}}
        </a>

        <div class=" hs-dropdown relative inline-flex">
          <button id="hs-dropdown-with-dividers" type="button"
                  class=" hs-dropdown-toggle py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 text-gray-800 shadow-2xs bg-white focus:outline-hidden disabled:opacity-50 cursor-pointer"
                  aria-haspopup="menu" aria-expanded="false" aria-label="Dropdown">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
              <circle cx="12" cy="6" r="4" fill="#000"/>
              <path fill="#000" d="M20 17.5c0 2.485 0 4.5-8 4.5s-8-2.015-8-4.5S7.582 13 12 13s8 2.015 8 4.5"/>
            </svg>

          </button>

          <div
            class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden min-w-60 bg-white shadow-md rounded-lg mt-2 divide-y divide-gray-200"
            role="menu" aria-orientation="vertical" aria-labelledby="hs-dropdown-with-dividers">
            <div class="p-1 space-y-0.5">
              <a
                class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100"
                href="{{ route('profile.index') }}">
                Profile
              </a>
              <form method="post" action="{{ route('logout') }}" class="group">
                @csrf
                <button
                  type="submit"
                  class="w-full text-left flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 hover:bg-gray-100 focus:outline-hidden focus:bg-gray-100 border-none bg-transparent"
                >
                  Sign Out
                </button>
              </form>

            </div>
          </div>
        </div>
        {{--Button--}}

      </div>

      <div id="hs-navbar-alignment"
           class="hs-collapse hidden overflow-hidden transition-all duration-300 basis-full grow sm:grow-0 sm:basis-auto sm:block sm:order-2"
           aria-labelledby="hs-navbar-alignment-collapse">
        <div class="flex flex-col gap-5 mt-5 sm:flex-row sm:items-center sm:mt-0 sm:ps-5">
          {{--          <a--}}
          {{--            class="font-medium text-gray-600 hover:text-gray-400 focus:outline-hidden focus:text-gray-400 "--}}
          {{--            href="#">Dashboard</a>--}}
          <a class="font-medium text-gray-600 hover:text-gray-400 focus:outline-hidden focus:text-gray-400"
             href="{{ route('book.index') }}">Buku</a>
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
          </div>


          {{--          <div class=" md:hidden lg:hidden grid grid-cols-2 gap-3">--}}
          {{--            --}}{{--Button--}}

          {{--            --}}{{--            Notification--}}
          {{--            <button type="button"--}}
          {{--                    class="relative px-3 py-2 flex justify-center items-center  border border-gray-200  text-sm font-medium rounded-lg bg-gray-100 hover:bg-gray-300 text-white focus:outline-hidden disabled:opacity-50 cursor-pointer">--}}
          {{--              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 512 512">--}}
          {{--                <path fill="#000"--}}
          {{--                      d="M440.08 341.31c-1.66-2-3.29-4-4.89-5.93c-22-26.61-35.31-42.67-35.31-118c0-39-9.33-71-27.72-95c-13.56-17.73-31.89-31.18-56.05-41.12a3 3 0 0 1-.82-.67C306.6 51.49 282.82 32 256 32s-50.59 19.49-59.28 48.56a3.1 3.1 0 0 1-.81.65c-56.38 23.21-83.78 67.74-83.78 136.14c0 75.36-13.29 91.42-35.31 118c-1.6 1.93-3.23 3.89-4.89 5.93a35.16 35.16 0 0 0-4.65 37.62c6.17 13 19.32 21.07 34.33 21.07H410.5c14.94 0 28-8.06 34.19-21a35.17 35.17 0 0 0-4.61-37.66M256 480a80.06 80.06 0 0 0 70.44-42.13a4 4 0 0 0-3.54-5.87H189.12a4 4 0 0 0-3.55 5.87A80.06 80.06 0 0 0 256 480"/>--}}
          {{--              </svg>--}}
          {{--              <span--}}
          {{--                class="absolute top-0 end-0 inline-flex items-center size-3 rounded-full  text-xs font-medium transform -translate-y-1/2 translate-x-1/2 bg-red-500 text-white">--}}
          {{--                <span class="sr-only">Badge value</span>--}}
          {{--              </span>--}}
          {{--            </button>--}}

          {{--            --}}{{--            Cart--}}
          {{--            <a href="{{ route('cart.index') }}"--}}
          {{--               class="relative px-3 py-2 flex justify-center items-center border border-gray-200 text-sm font-medium rounded-lg bg-gray-100 hover:bg-gray-300 text-white focus:outline-hidden disabled:opacity-50 cursor-pointer">--}}
          {{--              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">--}}
          {{--                <path fill="#000"--}}
          {{--                      d="M17 18c-1.11 0-2 .89-2 2a2 2 0 0 0 2 2a2 2 0 0 0 2-2a2 2 0 0 0-2-2M1 2v2h2l3.6 7.59l-1.36 2.45c-.15.28-.24.61-.24.96a2 2 0 0 0 2 2h12v-2H7.42a.25.25 0 0 1-.25-.25q0-.075.03-.12L8.1 13h7.45c.75 0 1.41-.42 1.75-1.03l3.58-6.47c.07-.16.12-.33.12-.5a1 1 0 0 0-1-1H5.21l-.94-2M7 18c-1.11 0-2 .89-2 2a2 2 0 0 0 2 2a2 2 0 0 0 2-2a2 2 0 0 0-2-2"/>--}}
          {{--              </svg>--}}
          {{--            </a>--}}
          {{--            --}}{{--Button--}}
          {{--          </div>--}}


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
  <footer class="w-full mt-10">
    <div class="p-5 bg-blue-600 text-sm flex justify-center text-white font-semibold">
      <p>© {{ date('Y') }} SMP Alfalah Assalam. All rights reserved.</p>
    </div>
  </footer>
  {{--    Footer --}}

  <wireui:scripts/>
  <script src="//unpkg.com/alpinejs" defer></script>
  @livewireScripts
</body>
</html>
