<?php

namespace App\Livewire\Courses;

use App\Models\Course_class;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\On;
use Livewire\Component;

class Index extends Component
{

    public $courses;
    public $program_id;
    public $grade = 1;
    public $year;
    public $semester;
    public $scheduleCourses;

    public function mount()
    {

        $this->scheduleCourses = collect();

        $this->program_id = Session::get('program');
        $this->year = Session::get('year');
        $this->semester = Session::get('semester');
        $this->loadCourses();

    }


    public function loadCourses()
    {
        $this->courses = Course_class::query()->whereHas('course', function ($query) {
            return $query->where('year', $this->year)->where('semester', $this->semester);
        })->where('program_id', $this->program_id)->where('grade', $this->grade)->with('course')->get();
    }

    #[On('filterUpdated')]
    public function applySidebarFilters($filters)
    {
        $this->program_id = $filters['program'];
        $this->year = $filters['year'];
        $this->semester = $filters['semester'];
        $this->loadCourses();
    }

    #[On('gradeUpdated')]
    public function applyGrade($grade)
    {
        $this->grade = $grade;
        $this->loadCourses();
    }

    #[On('addToSchedule')]
    public function addToSchedule($courseId)
    {
        $this->scheduleCourses->push($courseId);

        $this->removeFromCourse($courseId);
    }

    public function removeFromCourse($courseId)
    {
        $this->courses = $this->courses->filter(fn($course) => $course->id != $courseId);
    }

    public function removeFromSchedule($courseId)
    {

    }
    public function render()
    {
        return view('livewire.courses.index');
    }
}
