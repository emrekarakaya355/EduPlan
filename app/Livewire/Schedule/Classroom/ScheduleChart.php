<?php

namespace App\Livewire\Schedule\Classroom;

use App\Livewire\Schedule\Shared\BaseSchedule;
use App\Services\ScheduleSlotProviders\ClassroomBasedScheduleSlotProvider;
use App\Traits\UsesScheduleDataFormatter;
use Livewire\Component;

class ScheduleChart extends BaseSchedule
{
    use UsesScheduleDataFormatter;
    public $viewMode = 'classroom';
    public $classroomId;
    public $classroomName;
    public $scheduleData = [];
    public $days = [];
    public $asModal = false;

    /**
     * @return mixed
     */
    protected function initializeProvider()
    {
        $this->provider = new ClassroomBasedScheduleSlotProvider($this->classroomId ?? -1);
    }

    public function render()
    {
        return view('livewire.schedule.classroom.schedule-chart');
    }


}
