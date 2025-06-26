<?php

namespace App\Livewire\Classrooms;

use App\Models\Building;
use App\Models\Classroom;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class TotalList extends Component
{

    public $selectedBuildingId;
    public $classrooms;

    public $selectedClassroomId;

    public function mount($selectedBuildingId)
    {


        $this->classrooms = Classroom::where('building_id', $selectedBuildingId)
            ->with('scheduleSlots')
            ->get()
            ->sortByDesc(function ($classroom) {
                return $classroom->UniqueUsedTimeSlotsCount;
            })
            ->values() ?? collect();
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
