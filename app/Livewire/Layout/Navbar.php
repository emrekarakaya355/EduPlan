<?php

namespace App\Livewire\Layout;

use App\Livewire\Actions\Logout;
use Livewire\Component;

class Navbar extends Component
{
    public bool $sidebarOption;

    public function __construct($sidebarOption = true){
        $this->sidebarOption = $sidebarOption;
    }
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
    public function render()
    {
        return view('livewire.layout.navbar');
    }
}
