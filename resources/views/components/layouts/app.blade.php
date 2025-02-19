<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>{{ $title ?? 'Page Title' }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])

    </head>
    <body  class="font-sans antialiased">
    <header class="flex items-center h-20 md:h-auto" x-data="{ open: false }">
        <nav class="relative flex items-center w-full px-4">
            <!-- Mobile Header -->
            <div class="inline-flex items-center justify-center w-full md:hidden">
                <a href="#" @click="open = true" @click.away="open = false" class="absolute left-0 pl-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 stroke-blue-600" fill="currentColor" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h8m-8 6h16" />
                    </svg>
                </a>
                <a href="#">
                    <h2 class="text-2xl font-extrabold text-blue-600">{{ config('app.name', 'Laravel') }}</h2>
                </a>
            </div>

            <livewire:layout.navigation />

        </nav>
    </header>

    <div class="min-h-screen bg-gray-100 dark:bg-gray-900" x-data="{ sidebarOpen: true }" @sidebar-toggle.window="sidebarOpen = $event.detail">

        <main class="pt-16   min-h-screen w-full" :class="{ 'pl-64': sidebarOpen, 'pl-0': !sidebarOpen }">
            <div class="p-6 grid grid-rows-[auto_1fr_auto] grid-cols-[1fr_auto] gap-4 min-h-[calc(100vh-4rem)]">
                <!-- Üst Kısım -->
                @if(isset($top))
                    <div class="col-span-2 bg-white mb-4 rounded-lg shadow-sm">
                        {{ $top }}
                    </div>
                @endif

                <div class="flex">
                    <div class="bg-white p-4 rounded-lg shadow-sm flex-1">
                        {{ $slot }}
                    </div>

                    @if(isset($right))
                        <div class="bg-white p-4 rounded-lg shadow-sm" style="flex: 0 0 20%; margin-left: 20px;">
                            {{ $right }}
                        </div>
                    @endif
                </div>

                <!-- Alt Kısım -->
                @if(isset($foot))
                    <div class="col-span-2 bg-white p-4 rounded-lg shadow-sm" style="max-height: 30vh;">
                        {{ $foot }}
                    </div>
                @endif
            </div>
        </main>
    </div>
    </body>
</html>
