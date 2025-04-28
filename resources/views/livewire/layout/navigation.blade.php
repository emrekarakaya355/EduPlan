<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component
{
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<div x-data="{sidebarOpen: true }">

    <livewire:layout.navbar/>

    <div>
        <livewire:layout.sidebar/>
    </div>
    <!-- Mobile Sidebar (Overlay) -->
    <div x-show="open" class="lg:hidden fixed inset-0 z-30 bg-black bg-opacity-50" @click="open = false"></div>
    <aside x-show="open" class="lg:hidden fixed left-0 top-0 h-full w-64 bg-white shadow-md pt-16 z-40 transition-transform transform" :class="{ '-translate-x-full': !open, 'translate-x-0': open }">
        <div class="p-4 space-y-4">
            <x-nav-link href="{{ route('ubys') }}" :active="request()->routeIs('ubys')" class="block px-4 py-2 rounded hover:bg-gray-200">
                📚 Dersler
            </x-nav-link>
        </div>
    </aside>
</div>
