<?php

namespace App\Livewire\Courses;

use Livewire\Attributes\Modelable;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class CompactList extends Component
{
     public $courses;

    public  $scheduleCourses;

    public function mount(){
        $this->scheduleCourses = collect();

    }
    #[On('addToSchedule')]
    public function addToSchedule($courseId){
        $this->scheduleCourses->push($courseId);

        $this->removeFromCourse($courseId);
    }

    public function removeFromCourse($courseId){
        $this->courses = $this->courses->filter(fn($course) => $course->id != $courseId);
    }

    public function removeFromSchedule($courseId)
    {

    }

    public function render()
    {
        return view('livewire.courses.compact-list');
    }
}
