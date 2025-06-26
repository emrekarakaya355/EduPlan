<?php

namespace App\Livewire\Pages\Settings;

use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Ayarlar')]
class Index extends Component
{
    public function render()
    {
        return view('livewire.pages.settings.index');
    }
}
