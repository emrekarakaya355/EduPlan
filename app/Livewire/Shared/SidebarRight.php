<?php

namespace App\Livewire\Shared;

use Livewire\Component;

class SidebarRight extends Component
{
    public $activeTab = 'course';

    public function selectTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function render()
    {
        return view('livewire.shared.sidebar-right');
    }
}
