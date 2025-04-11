<?php

namespace App\Livewire\Schedule;

use App\Services\ScheduleSlotProviders\CourseBasedScheduleSlotProvider;
use Livewire\Component;
use App\Traits\UsesScheduleDataFormatter;

class CourseModal extends Component
{
    use UsesScheduleDataFormatter;
    public $viewMode = 'classroom';
    public $courseId;
    public $courseName;
    public $scheduleData = [];
    public $days = [];

    public function mount($courseId, $courseName )
    {
        $this->courseId = $courseId;
        $this->courseName = $courseName;
        $this->loadSchedule();
    }

    public function loadSchedule()
    {
        $provider = new CourseBasedScheduleSlotProvider($this->courseId);
        $this->scheduleData = $this->prepareScheduleSlotData($provider->getScheduleSlots());
        //$this->days = $this->formatScheduleData($this->scheduleData);
    }

    public function render()
    {
        return view('livewire.schedule.course.modal');
    }
}
