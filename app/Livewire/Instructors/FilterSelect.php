<?php

namespace App\Livewire\Instructors;

use App\Models\Course_class;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class FilterSelect extends Component
{
    public $unit_id, $year, $semester,$instructors;
    public $selectedInstructor;
    public $selectedInstructorName;

    public function mount($unit_id, $year, $semester)
    {
        $this->unit_id = empty($unit_id) ? -1 : $unit_id;
        $this->year = empty($year) ? 2000 : $year;
        $this->semester = empty($semester) ? 'Spring' : $semester;
        $this->loadInstructors();
        /*
        $foundInstructor = $this->instructors->firstWhere('id',  $this->selectedInstructor);
        if ($foundInstructor) {
            $this->selectedInstructorName = $foundInstructor->name;
        } else {
            $this->selectedInstructorName = '';
        }
        if($this->selectedInstructor){
            $this->dispatch('instructorSelected',id: $this->selectedInstructor,name: $this->selectedInstructorName);
        }*/

    }

    public function updatedSelectedInstructor($value){
        Session::forget('selectedCampusId');
        Session::forget('selectedBuildingId');
        Session::put('selectedInstructor', $this->selectedInstructor);
        $this->selectedInstructor = $value;
        $foundInstructor = $this->instructors->firstWhere('id', $value);
        if ($foundInstructor) {
            $this->selectedInstructorName = $foundInstructor->name;
        } else {
            $this->selectedInstructorName = '';
        }

        $this->dispatch('instructorSelected',id: $this->selectedInstructor,name: $this->selectedInstructorName);
    }

    public function loadInstructors()
    {
        $this->instructors = Course_class::with('instructor','program')
            ->whereHas('course', function ($query) {
                return $query->where('year',  $this->year)
                    ->where('semester',  $this->semester);
            })
            ->whereHas('program.bolum', function ($query) {
                return $query->where('birim_id', $this->unit_id ?? -1);
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
