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
<body class="flex flex-col min-h-screen"">

  {{--WireUI Notif--}}
  <x-notifications position="top-end"/>
  {{--WireUI Notif--}}

  {{--    Navbar --}}
  <header class="flex flex-wrap sm:justify-start sm:flex-nowrap w-full bg-white text-sm py-3 mb-10">
    <nav class="max-w-[85rem] w-full mx-auto px-4 flex flex-wrap basis-full items-center justify-between">
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
        <button type="button"
                class="hidden md:block py-2 px-3 items-center gap-x-2 text-sm font-medium rounded-lg border border-blue-600 text-blue-600 hover:border-blue-500 hover:text-blue-500 focus:outline-hidden focus:border-blue-500 focus:text-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:border-blue-500 dark:text-blue-500 dark:hover:text-blue-400 dark:hover:border-blue-400">
          Login
        </button>
        <button type="button"
                class="hidden md:block py-2 px-3 items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-hidden focus:bg-blue-700 focus:outline-hidden focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none">
          Sign Up
        </button>
      </div>
      <div id="hs-navbar-alignment"
           class="hs-collapse hidden overflow-hidden transition-all duration-300 basis-full grow sm:grow-0 sm:basis-auto sm:block sm:order-2"
           aria-labelledby="hs-navbar-alignment-collapse">
        <div class="flex flex-col gap-5 mt-5 sm:flex-row sm:items-center sm:mt-0 sm:ps-5">
          <a
            class="font-medium text-gray-600 hover:text-gray-400 focus:outline-hidden focus:text-gray-400 "
            href="#">Fitur</a>
          <a class="font-medium text-gray-600 hover:text-gray-400 focus:outline-hidden focus:text-gray-400" href="#">Layanan</a>
          <a class="font-medium text-gray-600 hover:text-gray-400 focus:outline-hidden focus:text-gray-400" href="#">Lokasi</a>

          <div class=" md:hidden lg:hidden grid grid-cols-2 gap-3">
            <button type="button"
                    class="py-2 px-3 inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-lg border border-blue-600 text-blue-600 hover:border-blue-500 hover:text-blue-500 focus:outline-hidden focus:border-blue-500 focus:text-blue-500 disabled:opacity-50 disabled:pointer-events-none">
              Login
            </button>
            <button type="button"
                    class="py-2 px-3 inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-hidden focus:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none">
              Sign Up
            </button>
          </div>
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
</html>
