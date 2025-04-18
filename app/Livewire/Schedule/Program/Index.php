<?php

namespace App\Livewire\Schedule\Program;

use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Ders Programı')]
class Index extends Component
{



    public function render()
    {
        return view('livewire.schedule.program.index');
    }
}
