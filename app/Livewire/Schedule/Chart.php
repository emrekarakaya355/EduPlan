<?php

namespace App\Livewire\Schedule;

use App\Models\Schedule;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\On;
use Livewire\Component;
use Nette\Utils\ArrayList;

class Chart extends Component
{

    public $program, $year, $semester;
    public $grade = 1;
    public $schedule;
    public $scheduleSlots;

    public function mount(): void
    {
        $this->program = Session::get('program');
        $this->year = Session::get('year');
        $this->semester = Session::get('semester');
        $this->loadSchedule();
    }

    public function loadSchedule(){
        if (empty($this->program)) {
            $this->schedule = null;
        } else {
            $query = Schedule::query();
            $query->where('program_id', $this->program);
            $query->where('year', $this->year);
            $query->where('semester', $this->semester);
            $query->where('grade', $this->grade);
            $query->whereHas('program.courseClasses', function ($q) {
                $q->where('grade', $this->grade);
            });
            $this->schedule = $query->with(['scheduleSlots', 'program.courseClasses.instructor', 'program.courseClasses.course', 'program.courseClasses' => function ($q) {
                $q->where('grade', $this->grade);
            }])->first();

            $this->scheduleSlots = $this->schedule->scheduleSlots->groupBy('day','start_time');
        }
    }
    #[On('filterUpdated')]
    public function applyFilters($filters): void
    {
        $this->program = $filters['program'];
        $this->year = $filters['year'];
        $this->semester = $filters['semester'];
        $this->loadSchedule();
    }
    #[On('gradeUpdated')]
    public function applyGrade($grade): void
    {
        $this->grade = $grade;
        $this->loadSchedule();
    }

    public function render()
    {
        return view('livewire.schedule.chart');
    }
}
