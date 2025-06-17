<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>{{ $title ?? 'Page Title' }}</title>
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <livewire:styles />
    </head>
    <body  class="font-sans antialiased">

      {{ $slot }}

      @stack('scripts')
      @stack('styles')
      <livewire:scripts />

    </body>
</html>
