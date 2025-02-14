<?php

namespace App\Livewire\Courses;

use App\Models\Course;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class CourseCompactList extends Component
{
    #[Reactive]
    public  $courses;
    public function render()
    {
        return view('livewire.courses.course-compact-list');
    }
}
