<?php

namespace App\Livewire\Schedule\Shared;

use Livewire\Component;

class ChartCell extends Component
{
    public $day;
    public $time;
    public $slots;

    public function mount($day, $time, $slots)
    {
        $this->day = $day;
        $this->time = $time;
        $this->slots = $slots;
    }

    public function render()
    {
        return view('livewire.schedule.chart-cell');
    }
}
