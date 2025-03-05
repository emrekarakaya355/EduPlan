<?php

namespace App\Livewire\Courses;

use App\Models\Course;
use App\Models\Course_class;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class CourseCompactList extends Component
{
    public  $courses;
    public $program_id;
    public $grade = 1;
    public $year;
    public $semester;

    public function mount(){

        $this->program_id = Session::get('program');
        $this->year = Session::get('year');
        $this->semester = Session::get('semester');

    }

    #[On('filtersUpdated')]
    public function applySidebarFilters($filters)
    {
        $this->program_id = $filters['program_id'];
        $this->year = $filters['year'];
        $this->semester = $filters['semester'];
    }

    #[On('gradeUpdated')]
    public function applyGrade($grade){
        $this->grade = $grade;
    }
    public function render()
    {
        $this->courses = Course_class::query()->whereHas('course',function($query){
            return $query->where('year',$this->year)->where('semester',$this->semester);
        })->where('program_id', $this->program_id)->where('grade',$this->grade)->get();
        return view('livewire.courses.course-compact-list');
    }
}
