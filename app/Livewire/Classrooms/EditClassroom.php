<?php

namespace App\Livewire\Classrooms;

use App\Livewire\Forms\ClassroomForm;
use App\Models\Classroom;
use Livewire\Component;

class EditClassroom extends Component
{
    public ClassroomForm $form;

    public function mount(Classroom $classroom) {
        $this->form->setClassroom($classroom);
    }
    public function save() {
        $this->form->update();

        $this->redirect('/dashboard/schedule', navigate: true);
    }
    public function render()
    {
        return view('livewire.classrooms.edit-classroom');
    }
}
