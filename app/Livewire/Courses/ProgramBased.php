<?php

namespace App\Livewire\Courses;

use App\Models\Course_class;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class ProgramBased extends Component
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

        $this->program_id = Session::get('program') == '' ? -1 : Session::get('program');
        $this->year = Session::get('year');
        $this->semester = Session::get('semester');
        $this->loadCourses();

    }


    #[Computed]
    public function loadCourses()
    {
          return $this->courses = Course_class::query()->whereHas('course', function ($query) {
                return $query->where('year', $this->year)->where('semester', $this->semester);
            })->where('program_id', $this->program_id )->where('grade', $this->grade)->with('course')->get()
                ->filter(function ($course) {
                    return $course->unscheduled_hours > 0;
                });
    }



    public $selectedCourseId = null;
    public $selectedCourseName = '';

    protected $viewMode = 'course';
    public $showCourseModal = false;

    #[On('open-course-modal')]
    public function openClasroomModal($courseId,$courseName): void
    {
        $this-> selectedCourseId = $courseId;
        $this->selectedCourseName = $courseName;
        $this->showCourseModal = true;
    }
    #[On('close-modal')]
    public function closeModal(): void
    {
        $this->showCourseModal = false;
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
    public function removeFromCourseList(): void
    {
        $this->loadCourses();
    }

    #[On('removeFromSchedule')]
    public function addToCourseList(): void
    {
        $this->loadCourses();

    }
    public function render()
    {
         return view('livewire.courses.program-based');
    }
}
