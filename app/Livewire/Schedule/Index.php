<?php

namespace App\Livewire\Schedule;

use App\Enums\DayOfWeek;
use App\Livewire\UbysAktar;
use App\Models\Course_class;
use App\Models\ogrenci;
use App\Models\Schedule;
use App\Models\ScheduleSlot;
use App\Services\Adapters\UbysAdapter;
use App\Services\ScheduleService;
use Carbon\Carbon;
use Carbon\WeekDay;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Schedule')]
class Index extends Component
{


    public function render()
    {
        return view('livewire.schedule.index');
    }
}
