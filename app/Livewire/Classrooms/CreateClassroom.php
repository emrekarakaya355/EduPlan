<?php

namespace App\Livewire\Classrooms;

use App\Livewire\Forms\ClassroomForm;
use App\Models\Building;
use Livewire\Component;

class CreateClassroom extends Component
{
    public ClassroomForm $form;
    public $selectedBuildingId;
    public function mount($selectedBuildingId) {
       $this->selectedBuildingId = $selectedBuildingId;
    }
    public function save() {

        $this->form->store();
        session()->flash('success', 'Sınıf Oluşturuldu.');
        $this->dispatch('toggleForm');
    }
    public function render()
    {
        return view('livewire.classrooms.create-classroom');
    }
}
