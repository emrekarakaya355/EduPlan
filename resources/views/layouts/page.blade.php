<div class=" bg-gray-100 dark:bg-gray-900" x-data="{ sidebarOpen: true }" @sidebar-toggle.window="sidebarOpen = $event.detail">

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
                <livewire:layout.navigation/>
        </nav>
    </header>

    <main class="pt-16 min-h-screen w-full"  :class="{ 'pl-64': sidebarOpen, 'pl-0': !sidebarOpen }">
        <div class="p-6 flex gap-4 min-h-[calc(100vh-4rem)]" style="flex-direction: column">
            <!-- Üst Kısım -->
            <div class="flex  space-x-8 flex-grow-0"  >
                @if(isset($top))
                    <div class="flex-1 bg-white rounded-lg shadow-sm overflow-hidden sticky top-10" >
                        {{ $top }}
                    </div>
                @endif

            </div>
            <div class="flex space-x-8">
                <div class="bg-white rounded-lg shadow-sm flex-1 ">
                    {{ $slot }}
                </div>
                <div class="flex flex-col w-[20%] gap-2 ">

                    @if(isset($right))

                        <div class="bg-white rounded-lg shadow-sm min-h-[calc(100vh-5.5rem)] overflow-y-auto sticky top-20" >
                            @if(isset($detay))
                                <div class="min-h-32">
                                    {{ $detay }}
                                </div>
                            @endif
                            {{ $right }}
                        </div>
                    @endif
                </div>

            </div>

            <!-- Alt Kısım -->
            @if(isset($foot))
                <div class="flex-1 bg-white p-4 rounded-lg shadow-sm" style="max-height: 30vh;">
                    {{ $foot }}
                </div>
            @endif
        </div>
    </main>

</div>
