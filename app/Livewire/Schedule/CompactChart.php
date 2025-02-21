<?php

namespace App\Livewire\Schedule;

use App\Models\Bolum;
use App\Models\Program;
use App\Models\Schedule;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class CompactChart extends Component
{
    public $unit, $year, $semester;
    public $courseClasses = [];

    public $programSchedules;
    protected $listeners = ['filterUpdated' => 'applyFilters'];

    public function mount()
    {
        $this->courseClasses = collect();
        $this->unit = Session::get('unit', '');
        $this->year = Session::get('year', '');
        $this->semester = Session::get('semester', '');
    }


    public function applyFilters($filters)
    {
        $this->unit = $filters['unit'];
        $this->year = $filters['year'];
        $this->semester = $filters['semester'];

    }

    public function render()
    {

        if (empty($this->unit)) {
            $this->courseClasses = collect();
            $this->programSchedules = collect();
        } else {
            $programSchedules = Program::whereHas('bolum', function ($query) {
                $query->where('birim_id', $this->unit);
            })
                ->with(['schedules'])
                ->get()
                ->flatMap(function ($program) {
                    return $program->schedules->groupBy('grade')->mapWithKeys(function ($schedules, $grade) use ($program) {
                        return [
                            "{$program->name} - {$grade}" => $schedules
                        ];
                    });
                });

            $this->courseClasses = $schedule->program->courseClasses ?? [];
            $this->programSchedules = $programSchedules;

        }

        return view('livewire.schedule.compact-chart');
    }
}
