<?php

namespace App\Livewire\Schedule;

use App\Models\Schedule;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class Chart extends Component
{

    public $program, $year, $semester;
    public $grade = 1;
    public $courseClasses = [];
    protected $listeners = ['filterUpdated' => 'applyFilters'];

    public function mount()
    {
        $this->courseClasses = collect();
        $this->program = Session::get('program', '');
        $this->year = Session::get('year', '');
        $this->semester = Session::get('semester', '');
    }


    public function applyFilters($filters)
    {
        $this->program = $filters['program'];
        $this->year = $filters['year'];
        $this->semester = $filters['semester'];

    }

    public function render()
    {
        if (empty($this->program)) {
            $schedule = collect();
            $this->courseClasses = collect();
        } else {
            $query = Schedule::query();
            $query->where('program_id', $this->program);

            $query->where('year', $this->year);

            $query->where('semester', $this->semester);

            $query->where('grade', $this->grade);
            $query->whereHas('program.courseClasses', function ($q) {
                $q->where('grade', $this->grade);
            });
            $schedule = $query->with(['program', 'scheduleSlots', 'program.courseClasses.instructor', 'program.courseClasses.course', 'program.courseClasses' => function ($q) {
                $q->where('grade', $this->grade);
            }])->first();

            $this->courseClasses = $schedule->program->courseClasses ?? [];

        }
        return view('livewire.schedule.chart', compact('schedule'));
    }
}
