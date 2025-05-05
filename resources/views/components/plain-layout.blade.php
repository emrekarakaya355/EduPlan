
<div class="min-h-screen bg-gray-100 dark:bg-gray-900">
    <header class="flex items-center h-20 md:h-auto">
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
            <div >

            <livewire:layout.navbar :sidebarOption="false"/>
            </div>
        </nav>
    </header>

    <main class="pt-16  min-h-screen w-full" >
        <div class="p-6 flex gap-4 min-h-[calc(100vh-4rem)]" style="flex-direction: column">
            <div class="flex  space-x-8 flex-grow-0"  >
                @if(isset($top))
                    <div class="flex-1 bg-white rounded-lg shadow-sm overflow-hidden" >
                        {{ $top }}
                    </div>
                @endif
                @if(isset($detay))
                    <div class="bg-white rounded-lg overflow-hidden" style="flex: 0 0 15%; height: 120px;">
                        {{ $detay }}
                    </div>
                @endif
            </div>
            <div class="flex space-x-8">
                <div class="bg-white rounded-lg shadow-sm flex-1 ">
                    {{ $slot }}
                </div>

                @if(isset($right))
                    <div class="bg-white rounded-lg shadow-sm overflow-hidden" style="flex: 0 0 15%;">
                        {{ $right }}
                    </div>
                @endif
            </div>

            @if(isset($foot))
                <div class="flex-1 bg-white p-4 rounded-lg shadow-sm" style="max-height: 30vh;">
                    {{ $foot }}
                </div>
            @endif
        </div>
    </main>
</div>
