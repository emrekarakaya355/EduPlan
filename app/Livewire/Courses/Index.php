<?php

namespace App\Livewire\Courses;

use App\Models\Course_class;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Computed;
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

    public $removedCourses;

    public function mount(): void
    {
        $this->scheduleCourses = collect();

        $this->program_id = Session::get('program');
        $this->year = Session::get('year');
        $this->semester = Session::get('semester');
        $this->loadCourses();

    }


    #[Computed]
    public function loadCourses()
    {
          return $this->courses = Course_class::query()->whereHas('course', function ($query) {
                return $query->where('year', $this->year)->where('semester', $this->semester);
            })->where('program_id', $this->program_id)->where('grade', $this->grade)->with('course')->get()
                ->filter(function ($course) {
                    return $course->unscheduled_hours > 0;
                });
    }



    #[On('filterUpdated')]
    public function applySidebarFilters($filters): void
    {
        $this->program_id = $filters['program'];
        $this->year = $filters['year'];
        $this->semester = $filters['semester'];
        $this->loadCourses();
    }

    #[On('gradeUpdated')]
    public function applyGrade($grade): void
    {
        $this->grade = $grade;
        $this->loadCourses();
    }

    #[On('addToSchedule')]
    public function removeFromCourseList($courseId): void
    {
        $this->loadCourses();
    }

    #[On('removeFromSchedule')]
    public function addToCourseList($courseId): void
    {
        $this->loadCourses();

    }

    public function render()
    {
        return view('livewire.courses.index');
    }
}
