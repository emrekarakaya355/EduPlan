<?php

namespace App\Livewire\Schedule;

use App\Services\ScheduleSlotProviders\ClassroomBasedScheduleSlotProvider;
use Livewire\Component;
use App\Traits\UsesScheduleDataFormatter;

class ClassroomModal extends Component
{
    use UsesScheduleDataFormatter;
    public $viewMode = 'classroom';
    public $classromId;
    public $classroomName;
    public $scheduleData = [];
    public $days = [];

    public function mount($classroomId, $classroomName )
    {
        $this->classromId = $classroomId;
        $this->classroomName = $classroomName;
        $this->loadSchedule();
    }

    public function loadSchedule()
    {
        $provider = new ClassroomBasedScheduleSlotProvider($this->classromId);
        $this->scheduleData = $this->prepareScheduleSlotData($provider->getScheduleSlots());
        //$this->days = $this->formatScheduleData($this->scheduleData);
    }

    public function render()
    {
        return view('livewire.schedule.classroom.modal');
    }
}
