<?php

namespace App\Livewire\Instructors;

use App\Models\Course_class;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class FilterSelect extends Component
{
    public $unit_id, $year, $semester,$instructors;

    public $selectedInstructor;

    public function mount($unit_id, $year, $semester)
    {
        $this->unit_id = $unit_id;
        $this->year = $year;
        $this->semester = $semester;
        $this->loadInstructors();

        $this->selectedInstructor = Session::get('selectedInstructor');
        if($this->selectedInstructor){
            $this->dispatch('instructorSelected',id: $this->selectedInstructor);
        }

    }

    public function updatedSelectedInstructor($value){
        Session::forget('selectedCampus');
        Session::forget('selectedBuilding');
        Session::put('selectedInstructor', $this->selectedInstructor);
        $this->selectedInstructor = $value;
        $this->dispatch('instructorSelected',id: $this->selectedInstructor);
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
            ->sortBy('adi')
            ->filter()
            ->unique('id')
            ->values();
    }
    public function render()
    {
        return view('livewire.instructors.filter-select');
    }
}
