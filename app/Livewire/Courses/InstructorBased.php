<?php

namespace App\Livewire\Courses;

use App\Models\Course;
use App\Models\Course_class;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class InstructorBased extends Component
{
    public $courses;
    public $year;
    public $semester;
    public $instructorId;

    public function mount($instructorId): void
    {

        $this->year = Session::get('year');
        $this->semester = Session::get('semester');
        $this->instructorId = $instructorId;
        $this->courses = Course_class::query()->whereHas('course',function($query){
            return $query->where('year', $this->year)->where('semester', $this->semester)->where('instructorId', $this->instructorId ?? -1);
        })
            ->distinct('external_id')
            ->with('program')->get()
            ->filter(function ($course) {
            return $course->unscheduled_hours > 0;
        });;
      }
    public function render()
    {
        return view('livewire.courses.instructor-based');
    }
}
