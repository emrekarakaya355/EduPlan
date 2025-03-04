<?php

namespace App\Livewire\Courses;

use App\Models\Course;
use App\Models\Course_class;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class CourseCompactList extends Component
{
    public  $courses;

    protected $listeners = ['filterUpdated' => 'applyFilters'];


    public function mount(){


        $this->courses = Course_class::query()->whereHas('course',function($query){
                return $query->where('year',Session('year'))->where('semester',Session('semester'));
        })->where('program_id',Session('program'))->get();
    }

    public function applyFilters($filters)
    {

        $this->courses = Course_class::query()->whereHas('course',function($query) use ($filters){
            return $query->where('year',$filters['year'])->where('semester',$filters['semester']);
        })->where('program_id', $filters['program'])->get();

    }
    public function render()
    {
        return view('livewire.courses.course-compact-list');
    }
}
