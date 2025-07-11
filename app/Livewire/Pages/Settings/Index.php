<?php

namespace App\Livewire\Pages\Settings;

use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Ayarlar')]
class Index extends Component
{
    public string $activeTab = 'time-settings';

    public function setActiveTab(string $tab)
    {
        $this->activeTab = $tab;
    }
    public function render()
    {
        return view('livewire.pages.settings.index');
    }
}
