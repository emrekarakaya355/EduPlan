<?php


namespace App\Livewire\Schedule\Shared;

use App\Contracts\ScheduleSlotProviderInterface;
use App\Models\Program;
use App\Traits\UsesScheduleDataFormatter;
use Livewire\Component;

abstract class BaseSchedule extends Component
{
    use UsesScheduleDataFormatter;
    protected ScheduleSlotProviderInterface $provider;

    protected $viewMode;
    public $schedule;
    public $scheduleData;
    public bool $isLocked = false;
    public ?int $lockedByUserId;
    public $days;
    public $showSaturday = false;
    public $saturdayDisabled = false;
    public $showSunday = false;
    public $sundayDisabled = false;
    public $grades = [];
    public $grade = 1;
    public $program, $year, $semester;

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
        } else {
            $this->days = collect($this->days)
                ->reject(fn ($day) => $day['value'] === 7)
                ->values()
                ->toArray();

            foreach ($this->scheduleData as $time => &$dayData) {
                unset($dayData[6]);
            }
        }
        unset($dayData);


    }
    public function updatedShowSaturday(){
        if ($this->showSaturday) {
            if (!collect($this->days)->contains('value', 6)) {
                $this->days[] = ['label' => 'Cumartesi', 'value' => 6];
            }
            foreach ($this->scheduleData as $time => &$dayData) {
                $dayData[5] = $dayData[5] ?? 0;
            }

        } else {
            $this->days = collect($this->days)
                ->reject(fn ($day) => $day['value'] === 6)
                ->values()
                ->toArray();

            foreach ($this->scheduleData as $time => &$dayData) {
                unset($dayData[5]);
            }

        }
        unset($dayData);

    }
    public function loadSchedule()
    {
        $this->initializeProvider();
        $this->schedule = $this->provider->getSchedule();
        $this->isLocked = $this->schedule?->is_locked ?? false;
        $this->lockedByUserId = $this->schedule?->locked_by_user_id;

        $data = $this->prepareScheduleSlotData($this->provider);
        $this->scheduleData = $data['scheduleData'];

        $this->days = $data['days'];

        if($this->days->contains('value', 6)){
            $this->showSaturday =true;
            $this->saturdayDisabled = true;
        }else{
            $this->showSaturday =false;
            $this->saturdayDisabled = false;
        }
        if($this->days->contains('value', 7)){
            $this->showSunday =true;
            $this->sundayDisabled = true;
        }else{
            $this->showSunday =false;
            $this->sundayDisabled = false;
        }
      }
    public function render()
    {
         return view("livewire.schedule.{$this->viewMode}.index");
    }
}
