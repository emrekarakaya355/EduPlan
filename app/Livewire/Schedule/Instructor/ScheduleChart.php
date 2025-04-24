<?php

namespace App\Livewire\Schedule\Instructor;
use App\Services\ScheduleSlotProviders\InstructorBasedScheduleSlotProvider;
use App\Traits\UsesScheduleDataFormatter;
use Livewire\Component;

class ScheduleChart extends Component
{
    use UsesScheduleDataFormatter;
    public $viewMode = 'instructor';
    public $instructorId;
    public $instructorName;
    public $asModal;
    public $scheduleData = [];
    public $days = [];

    public function mount($instructorId, $instructorName,$asModal = false )
    {
        $this->instructorId = $instructorId;
        $this->instructorName = $instructorName;
        $this->asModal = $asModal;
        $this->loadSchedule();
    }

    public function loadSchedule()
    {
        $provider = new InstructorBasedScheduleSlotProvider($this->instructorId ?? -1);
        $this->scheduleData = $this->prepareScheduleSlotData($provider->getScheduleSlots());
        //$this->days = $this->formatScheduleData($this->scheduleData);
    }

    public function render()
    {
        return view('livewire.schedule.instructor.schedule-chart');
    }
}
