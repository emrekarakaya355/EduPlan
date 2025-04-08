<?php

namespace App\Livewire\Classrooms;

use App\Livewire\Forms\ClassroomForm;
use App\Models\Building;
use Livewire\Component;

class CreateClassroom extends Component
{
    public ClassroomForm $form;
    public $selectedBuilding;
    public $buildings;
    public function mount() {
       $this->selectedBuilding = session()->get('selectedBuilding');
       $this->buildings = Building::all();
       $selected = $this->buildings->firstWhere('name', $this->selectedBuilding);
       if ($selected) {
            $this->form->building_id = $selected->id;
       }
    }
    public function save() {

        $this->form->store();
        $this->form->reset();
        session()->flash('success', 'Sınıf Oluşturuldu.');
        dd(1);
        $this->dispatch('close-create-classroom-form');
    }
    public function render()
    {
        return view('livewire.classrooms.create-classroom');
    }
}
