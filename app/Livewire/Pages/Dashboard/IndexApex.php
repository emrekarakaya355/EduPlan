<?php

namespace App\Livewire\Pages\Dashboard;

use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Ana Sayfa')]
class IndexApex extends Component
{

    public function render()
    {
        return view('livewire.pages.dashboard.index-apex');
    }
}
