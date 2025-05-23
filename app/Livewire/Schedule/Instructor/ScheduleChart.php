<?php

namespace App\Livewire\Schedule\Instructor;
use App\Livewire\Schedule\Shared\BaseSchedule;
use App\Services\ScheduleSlotProviders\InstructorBasedScheduleSlotProvider;
use App\Services\ScheduleSlotProviders\ProgramBasedScheduleSlotProvider;
use App\Traits\UsesScheduleDataFormatter;
use Livewire\Component;

class ScheduleChart extends BaseSchedule
{
    use UsesScheduleDataFormatter;
    public $viewMode = 'instructor';
    public $instructorId;
    public $instructorName;
    public $asModal = false;
    public $scheduleData = [];
    public $days = [];

    protected function initializeProvider()
    {
        $this->provider = new InstructorBasedScheduleSlotProvider($this->instructorId ?? -1);
    }
    public function render()
    {
        return view('livewire.schedule.instructor.schedule-chart');
    }

    /**
     * @return mixed
     */

}
