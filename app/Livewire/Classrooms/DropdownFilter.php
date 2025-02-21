<?php

namespace App\Livewire\Classrooms;

use App\Models\Building;
use Livewire\Component;

class DropdownFilter extends Component
{
    public $campusesAndBuildings;
    public $selectedCampus = null;
    public $selectedBuilding = null;
    public $selectedBuildingId = null;

    public function mount($campusesAndBuildings)
    {
        $this->campusesAndBuildings = $campusesAndBuildings;
        if(array_key_exists(session('campus'), $campusesAndBuildings)) {
            $this->selectedCampus = session('campus');
        } else {
            $this->selectedCampus = array_key_first($campusesAndBuildings);
            session()->forget('campus');
        }
        if (!empty($campusesAndBuildings[$this->selectedCampus]) && array_key_exists(session('selectedBuilding'), $campusesAndBuildings[$this->selectedCampus]))
        {
            $this->selectedBuilding = session('selectedBuilding');
        }
        else
        {
            $this->selectedBuilding = array_key_first($campusesAndBuildings[$this->selectedCampus]);
            session()->forget('selectedBuilding');
        }
        $this->dispatch('buildingSelected', buildingName: $this->selectedBuilding,campusName: $this->selectedCampus);


    }

    public function selectCampus($campusName)
    {
        $this->selectedCampus = $campusName;
        session(['selectedCampus' => $campusName]);

        /*Eğer campuse ait Bina varsa ilk sıradakini seçiyor  ve session kaydediyor yoksa unutuyor*/
        if (!empty($this->campusesAndBuildings[$campusName])) {
            $this->selectedBuilding = array_key_first($this->campusesAndBuildings[$campusName]);
            session(['selectedBuilding' => $this->selectedBuilding]); // Yeni binayı kaydet
        } else {
            $this->selectedBuilding = null;
            session()->forget('selectedBuilding'); // Bina yoksa temizle
        }
        $this->dispatch('campusSelected', campusName: $campusName);
    }

    public function updatedSelectedBuilding($buildingName)
    {
        $this->selectedBuilding = $buildingName;
        session(['selectedBuilding' => $buildingName]);
        $this->dispatch('buildingSelected', buildingName: $buildingName,campusName: $this->selectedCampus);
    }

    private function getCampusAndBuildings()
    {

    }

    public function render()
    {
        return view('livewire.classrooms.dropdown-filter');
    }
}
