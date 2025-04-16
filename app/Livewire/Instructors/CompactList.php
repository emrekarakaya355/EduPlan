<?php

namespace App\Livewire\Instructors;

use App\Models\Course_class;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class CompactList extends Component
{
    protected $listeners = ['filterUpdated' => 'applyFilters'];

    public $department, $year, $semester,$instructors;
    public $selectedInstructorId;

    public function mount()
    {
        $this->department = Session::get('department');
        $this->year = Session::get('year');
        $this->semester = Session::get('semester');
        $this->loadInstructors();
    }

    public function applyFilters($filters)
    {
        $this->department = $filters['department'];
        $this->year = $filters['year'];
        $this->semester = $filters['semester'];

    }

    public function selectInstructor($instructorId)
    {
        $this->selectedInstructorId = $instructorId;
    }

    public function loadInstructors()
    {
        $this->instructors = Course_class::with('instructor','program')
            ->whereHas('course', function ($query) {
               return $query->where('year', session('year'))
                    ->where('semester', session('semester'));
            })
            ->whereHas('program', function ($query) {
                return $query->where('bolum_id', $this->department);
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
