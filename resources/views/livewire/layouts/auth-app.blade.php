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
<body>


  {{--    Main Content --}}
  {{ $slot }}
  {{--    Main Content --}}

  <wireui:scripts />
  <script src="//unpkg.com/alpinejs" defer></script>
  @livewireScripts
</body>
</html>
