<?php

namespace App\Livewire;

use Livewire\Attributes\On;
use Livewire\Component;

class DynamicDetail extends Component
{
    public $detailData = [];

    public function render()
    {
        return view('livewire.dynamic-detail');
    }
}
