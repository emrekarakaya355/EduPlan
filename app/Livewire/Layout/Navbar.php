<?php

namespace App\Livewire\Layout;

use Livewire\Component;

class Navbar extends Component
{
    public bool $sidebarOption;

    public function __construct($sidebarOption = true){
        $this->sidebarOption = $sidebarOption;
    }
    public function render()
    {
        return view('livewire.layout.navbar');
    }
}
