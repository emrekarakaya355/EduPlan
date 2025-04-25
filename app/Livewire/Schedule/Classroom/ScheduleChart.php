<?php

namespace App\Livewire\Schedule\Classroom;

use App\Services\ScheduleSlotProviders\ClassroomBasedScheduleSlotProvider;
use App\Traits\UsesScheduleDataFormatter;
use Livewire\Component;

class ScheduleChart extends Component
{
    use UsesScheduleDataFormatter;
    public $viewMode = 'classroom';
    public $classromId;
    public $classroomName;
    public $scheduleData = [];
    public $days = [];
    public $asModal;
    public function mount($classroomId,$asModal = false )
    {
        $this->classromId = $classroomId;
        $this->asModal = $asModal;
        $this->loadSchedule();
    }

    public function loadSchedule()
    {
        $provider = new ClassroomBasedScheduleSlotProvider($this->classromId ?? -1);
        $this->scheduleData = $this->prepareScheduleSlotData($provider->getScheduleSlots());
        //$this->days = $this->formatScheduleData($this->scheduleData);
    }

    public function render()
    {
        return view('livewire.schedule.classroom.schedule-chart');
    }
}
