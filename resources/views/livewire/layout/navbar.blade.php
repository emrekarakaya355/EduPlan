<nav class="bg-white shadow-md fixed w-full z-10">
    <div class=" sm:px-6">
        <div class="flex items-center justify-between  h-16">
            @if($sidebarOption)
                <button @click="sidebarOpen = !sidebarOpen; $dispatch('sidebar-toggle', sidebarOpen)" :class="{ 'ml-64': sidebarOpen }" class="rounded-md text-gray-600 hover:bg-gray-200 transition duration-200">
                    <svg :class="{'hidden': sidebarOpen, 'block': !sidebarOpen}" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg :class="{'hidden': !sidebarOpen, 'block': sidebarOpen}" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
                @else
                <div class="ml-64">
                </div>
            @endif

                <div class="w-1/3 flex justify-center space-x-2">
                    <a href="{{ route('dashboard') }}" class="flex flex-col items-center group">
                        <i class="fa-solid fa-home text-4xl
            @if(request()->routeIs('dashboard')) text-blue-500 fa-beat-fade @else text-gray-500 @endif"></i>
                        <span class="text-xs mt-1 text-gray-600 group-hover:text-blue-500">Ana Sayfa</span>
                    </a>
                    <a href="{{ route('schedule') }}" class="flex flex-col items-center group">
                        <i class="fa-regular fa-calendar-days text-4xl
            @if(request()->routeIs('schedule')) text-blue-500 fa-beat-fade @else text-gray-500 @endif"></i>
                        <span class="text-xs mt-1 text-gray-600 group-hover:text-blue-500">Ders Programı</span>
                    </a>
                    <a href="{{ route('course-list') }}" class="flex flex-col items-center group">
                        <i class="fa-solid fa-book-open text-4xl
            @if(request()->routeIs('course-list')) text-blue-500 fa-beat-fade @else text-gray-500 @endif"></i>
                        <span class="text-xs mt-1 text-gray-600 group-hover:text-blue-500">Dersler</span>
                    </a>
                    <a href="{{ route('instructors') }}" class="flex flex-col items-center group">
                        <i class="fa-solid fa-people-carry-box text-4xl
            @if(request()->routeIs('instructors')) text-blue-500 fa-beat-fade @else text-gray-500 @endif"></i>
                        <span class="text-xs mt-1 text-gray-600 group-hover:text-blue-500">Haftalık Program</span>
                    </a>
                </div>


            <div></div>
            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke
                         ,++
                         +,+++++++++++++++++++++++++++++++++++++++++++++,+="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>


            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <div >
                    <livewire:shared.progress-bar  />

                </div>
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                            <!--div x-data="{{ json_encode(['name' => auth()->user()->adi]) }}" x-text="name" x-on:profile-updated.window="name = $event.detail.name"/-->

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <!-- Authentication -->
                        <button wire:click="logout" class="w-full text-start">
                            <x-dropdown-link>
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </button>
                    </x-slot>
                </x-dropdown>
            </div>

        </div>
    </div>
</nav>
