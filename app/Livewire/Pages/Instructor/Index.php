<?php

namespace App\Livewire\Pages\Instructor;
use App\Models\Course_class;
use Livewire\Attributes\On;
use Livewire\Component;

class Index extends Component
{
    public $selectedInstructorId ;
    public $selectedInstructorName = 'emre';
    #[On('instructorSelected')]
    public function instructorSelected($id)
    {
        $this->selectedInstructorId = $id;

    }
    public function render()
    {
        return view('livewire.pages.instructor.index');
    }
}
