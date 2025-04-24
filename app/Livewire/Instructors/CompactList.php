<?php

namespace App\Livewire\Instructors;

use App\Models\Course_class;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class CompactList extends Component
{
    protected $listeners = ['filterUpdated' => 'applyFilters'];

    public $unit_id, $year, $semester,$instructors;
    public $selectedInstructorId;

    public function mount()
    {
        $this->unit_id = Session::get('unit');
        $this->year = Session::get('year');
        $this->semester = Session::get('semester');
        $this->loadInstructors();
    }

    public function applyFilters($filters)
    {
        $this->unit_id = $filters['unit'];
        $this->year = $filters['year'];
        $this->semester = $filters['semester'];

    }

    public function loadInstructors()
    {
        $this->instructors = Course_class::with('instructor','program')
            ->whereHas('course', function ($query) {
               return $query->where('year', session('year'))
                    ->where('semester', session('semester'));
            })
            ->whereHas('program.bolum', function ($query) {
                return $query->where('birim_id', $this->unit_id);
            })
            ->get()
            ->pluck('instructor')
            ->filter()
            ->unique('id')
            ->values();
    }

    public function render()
    {
         return view('livewire.instructors.compact-list');
    }
}
