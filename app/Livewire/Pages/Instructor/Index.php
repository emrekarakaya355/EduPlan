<?php

namespace App\Livewire\Pages\Instructor;
use App\Models\Course_class;
use Livewire\Attributes\On;
use Livewire\Component;

class Index extends Component
{


    public $selectedInstructorId = null;
    public $selectedInstructorName = null;
    public $instructors = [];

    public function mount()
    {
        $this->loadInstructors();
    }
    public function loadInstructors()
    {
        $this->instructors = Course_class::with('instructor','program')
            ->whereHas('course', function ($query) {
                return $query->where('year', session('year'))
                    ->where('semester', session('semester'));
            })
            ->whereHas('program.bolum', function ($query) {
                return $query->where('birim_id', session('unit_id'));
            })
            ->get()
            ->pluck('instructor')
            ->filter()
            ->unique('id')
            ->values();
    }


    #[On('instructorSelected')]
    public function instructorSelected($id, $name)
    {
        $this->selectedInstructorId = $id;
        $this->selectedInstructorName = $name;
    }

    public function render()
    {
        return view('livewire.pages.instructor.index');
    }
}
