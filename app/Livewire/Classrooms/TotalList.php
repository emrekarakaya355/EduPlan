<?php

namespace App\Livewire\Classrooms;

use App\Models\Building;
use App\Models\Classroom;
use Livewire\Component;

class TotalList extends Component
{

    public $selectedBuildingId;
    public $classrooms;

    public $selectedClassroomId;

    public function mount($selectedBuildingId)
    {
        $this->classrooms = Classroom::where('building_id', $selectedBuildingId)->get() ?? [];
    }

    public function selectedClassroom($value ){
        $this->selectedClassroomId = $value;

        $this->dispatch('classroomSelected',id: $value);
    }

    public function render()
    {
        return view('livewire.classrooms.total-list');
    }
}
