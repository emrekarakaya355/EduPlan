<?php

namespace App\Livewire\Classrooms;

use App\Models\Building;
use App\Models\Campus;
use App\Models\Classroom;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\On;
use Livewire\Component;

class FilterSelect extends Component
{
    public $campuses;
    public $buildings = [];
    public $selectedCampus;
    public $selectedBuilding;

    public function mount()
    {
        $this->campuses = Campus::all();
        $this->selectedCampus = Session::get('selectedCampusId');
        $this->selectedBuilding = Session::get('selectedBuildingId');
        if (is_numeric($this->selectedCampus)) {
            $this->buildings = Building::where('campus_id', $this->selectedCampus)->get();
        }
        if($this->selectedBuilding){
            $this->dispatch('buildingSelected',id: $this->selectedBuilding);
        }
    }
    public function updatedSelectedCampus(){
        Session::forget('selectedInstructor');
        Session::put('selectedCampusId', $this->selectedCampus);
        Session::forget('selectedBuildingId');
        $this->selectedBuilding = null;

        if(!$this->selectedCampus || !$this->selectedBuilding == '' ){
            $this->buildings = [];
        }else
        {
            $this->buildings = Building::where('campus_id', $this->selectedCampus)->get();
        }
    }
    public function updatedSelectedBuilding()
    {
         Session::put('selectedBuildingId', $this->selectedBuilding);
         $this->dispatch('buildingSelected',id: $this->selectedBuilding);
    }

    public function render()
    {
        return view('livewire.classrooms.filter-select');
    }
}
