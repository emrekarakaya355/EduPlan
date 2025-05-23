<?php


namespace App\Livewire\Schedule\Shared;

use App\Contracts\ScheduleSlotProviderInterface;
use App\Enums\DayOfWeek;
use App\Models\Course;
use App\Models\Course_class;
use App\Traits\UsesScheduleDataFormatter;
use Livewire\Component;

abstract class BaseSchedule extends Component
{
    use UsesScheduleDataFormatter;
    protected ScheduleSlotProviderInterface $provider;

    protected $viewMode;
    public $schedule;
    public $scheduleData;
    public $days;
    public $showSaturday = false;
    public $saturdayDisabled = false;
    public $showSunday = false;
    public $sundayDisabled = false;
    abstract protected function initializeProvider();

    public function mount()
    {
        $this->initializeProvider();
        $this->loadSchedule();
    }

    public function updatedShowSunday(){
        if ($this->showSunday) {
            if (!collect($this->days)->contains('value', 7)) {
                $this->days[] = ['label' => 'Pazar', 'value' => 7];
            }
            foreach ($this->scheduleData as $time => &$dayData) {
                $dayData[6] = $dayData[6] ?? 0;
            }
            unset($dayData);
        } else {
            $this->days = collect($this->days)
                ->reject(fn ($day) => $day['value'] === 7)
                ->values()
                ->toArray();

            foreach ($this->scheduleData as $time => &$dayData) {
                unset($dayData[6]);
            }
            unset($dayData);
        }


    }

    public function updatedShowSaturday(){
        if ($this->showSaturday) {
            if (!collect($this->days)->contains('value', 6)) {
                $this->days[] = ['label' => 'Cumartesi', 'value' => 6];
            }
            foreach ($this->scheduleData as $time => &$dayData) {
                $dayData[5] = $dayData[5] ?? 0;
            }
            unset($dayData);

        } else {
            $this->days = collect($this->days)
                ->reject(fn ($day) => $day['value'] === 6)
                ->values()
                ->toArray();

            foreach ($this->scheduleData as $time => &$dayData) {
                unset($dayData[5]);
            }
            unset($dayData);

        }

    }
    public function loadSchedule()
    {
        $this->initializeProvider();
        $this->schedule = $this->provider->getSchedule();
        $data = $this->prepareScheduleSlotData($this->provider->getScheduleSlots());
        $this->scheduleData = $data['scheduleData'];
        $this->days = $data['days'];
        if($this->days->contains('value', 6)){
            $this->showSaturday =true;
            $this->saturdayDisabled = true;
        }
        if($this->days->contains('value', 7)){
            $this->showSunday =true;
            $this->sundayDisabled = true;
        }


     }
    public function render()
    {
         return view("livewire.schedule.{$this->viewMode}.index");
    }
}
