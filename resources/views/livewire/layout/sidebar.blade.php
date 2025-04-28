<aside class="fixed left-0 top-0 h-full w-64 bg-gray-900 text-white shadow-lg pt-16 z-40 transition-transform transform"
       :class="{ '-translate-x-full': !sidebarOpen, 'translate-x-0': sidebarOpen }">

    <livewire:layout.sidebar-filters />

    <div  class="flex justify-between">
        <div class="p-4 space-y-4">
            <x-responsive-nav-link :href="route('ubys')" :active="request()->routeIs('ubys')" wire:navigate>
                {{__('📚 Dersler')}}
            </x-responsive-nav-link>
        </div>
    </div>
</aside>
