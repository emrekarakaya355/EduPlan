<?php

namespace App\Livewire\Shared;

use Livewire\Component;

class DynamicDetail extends Component
{
    public $detailData = [];

    public function render()
    {
        return view('livewire.shared.dynamic-detail');
    }
}
