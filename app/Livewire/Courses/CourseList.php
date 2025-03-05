<?php

namespace App\Livewire\Courses;

use App\Models\Course_class;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Livewire\WithPagination;

class CourseList extends Component
{
    use WithPagination;

    protected $listeners = ['filterUpdated' => 'applyFilters'];

    public $program, $year, $semester;

    public function mount()
    {
        $this->program = Session::get('program');
        $this->year = Session::get('year');
        $this->semester = Session::get('semester');
    }

    public function applyFilters($filters)
    {
        $this->program = $filters['program'];
        $this->year = $filters['year'];
        $this->semester = $filters['semester'];

    }

    public function render()
    {
        if (empty($this->program)) {
            $courses = collect();
        } else {
            $query = Course_class::query();

            $query->where('program_id', $this->program);

            if ($this->year || $this->semester) {
                $query->whereHas('course', function ($q) {
                    if ($this->year) {
                        $q->where('year', $this->year);
                    }
                    if ($this->semester) {
                        $q->where('semester', $this->semester);
                    }
                });
            }

            $courses = $query->paginate(30);
        }
        return view('livewire.courses.course-list', compact('courses'));
    }
}
