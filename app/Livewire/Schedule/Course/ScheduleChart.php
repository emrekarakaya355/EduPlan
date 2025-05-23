<?php

namespace App\Livewire\Schedule\Course;

use App\Livewire\Schedule\Shared\BaseSchedule;
use App\Services\ScheduleSlotProviders\CourseBasedScheduleSlotProvider;
use App\Traits\UsesScheduleDataFormatter;

class ScheduleChart extends BaseSchedule
{
    use UsesScheduleDataFormatter;
    public $viewMode = 'classroom';
    public $courseId;
    public $courseName;
    public $scheduleData = [];
    public $days = [];

    protected function initializeProvider()
    {
        $this->provider = new CourseBasedScheduleSlotProvider($this->courseId);
        dd('Bunu Görürsenin Bana Haber Verin Emre Karakaya :):)');
    }

    public function render()
    {
        return view('livewire.schedule.course.schedule-chart');
    }


}
