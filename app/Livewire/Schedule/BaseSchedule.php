<?php


namespace App\Livewire\Schedule;

use App\Traits\UsesScheduleDataFormatter;
use Livewire\Component;
use App\Contracts\ScheduleSlotProviderInterface;

abstract class BaseSchedule extends Component
{
    use UsesScheduleDataFormatter;
    protected ScheduleSlotProviderInterface $provider;

    protected $viewMode;
    public $schedule;
    public $scheduleData;
    public $days;

    abstract protected function initializeProvider();

    public function mount()
    {
        $this->initializeProvider();
        $this->loadSchedule();
    }

    public function loadSchedule()
    {
        $this->initializeProvider();
        $this->schedule = $this->provider->getSchedule();
        $this->scheduleData = $this->prepareScheduleSlotData($this->provider->getScheduleSlots());
        //$this->days = $this->prepareScheduleSlotData($this->scheduleData);
    }

    public function render()
    {
         return view("livewire.schedule.{$this->viewMode}.index");
    }
}
