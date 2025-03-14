<?php

namespace App\Livewire\Schedule;

use App\Models\Schedule;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;
use Nette\Utils\ArrayList;

class Chart extends Component
{

    #[Reactive]
    public $schedule;

    #[Reactive]
    public $calendarData;


    public function render()
    {

        return view('livewire.schedule.chart');
    }
}
