<?php

namespace App\Livewire\Schedule;

use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Ders Programı')]
class Index extends Component
{
    public function render()
    {
        return view('livewire.schedule.index');
    }
}
