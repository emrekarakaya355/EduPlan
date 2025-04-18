<?php

namespace App\Livewire\Pages\Program;

use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Ders Programı')]
class Index extends Component
{
    public function render()
    {
        return view('livewire.pages.program.index');
    }
}
