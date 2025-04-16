<?php

namespace App\Livewire\Schedule\Partials;

use App\Models\Course_class;
use App\Models\ScheduleSlot;
use Livewire\Attributes\Lazy;
use Livewire\Component;

#[Lazy]
class ProgressBar extends Component
{
    public $year, $semester;
    public int $total = 0;
    public int $placed = 0;
    public int $percent = 0;
    public function mount(){
        $this->year = session('year') ?? 2000;
        $this->semester = session('semester') ?? 'Fall';

        $this->updateProgress();
    }
    public function updateProgress()
    {
        $this->total = Course_class::whereHas('course', function ($query) {
            $query->where('year', $this->year)
                ->where('semester', $this->semester);
        })->sum('duration');

        $this->placed = ScheduleSlot::whereHas('courseClass.course', function ($query) {
            $query->where('year', $this->year)
                ->where('semester', $this->semester);
        })->count();

        $this->percent = $this->total > 0 ? ceil(($this->placed / $this->total) * 100) : 0;

    }

    public function render()
    {
        return view('livewire.schedule.progress-bar');
    }
}
