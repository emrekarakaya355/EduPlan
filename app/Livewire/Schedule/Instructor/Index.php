<?php

namespace App\Livewire\Schedule\Instructor;
use App\Models\Course_class;
use App\Models\Instructor;
use Livewire\Component;

class Index extends Component
{

    public $search = '';
    public $selectedInstructorId = '';
    public $instructors;

    public function mount()
    {
        $this->loadInstructors();
    }

    public function updatedSearch()
    {
        $this->loadInstructors();
    }

    public function loadInstructors()
    {
        $this->instructors = Course_class::with('instructor')
            ->whereHas('course', function ($query) {
                $query->where('year', session('year'))
                    ->where('semester', session('semester'));
            })
            ->get()
            ->pluck('instructor')
            ->filter()
            ->unique('id')
            ->filter(function ($instructor) {
                return str_contains(strtolower($instructor->name), strtolower($this->search));
            })
            ->values();
    }
    public function render()
    {
        dd(1);
        return view('livewire.schedule.instructor.index');
    }
}
