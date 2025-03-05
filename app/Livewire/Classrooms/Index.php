<?php

namespace App\Livewire\Classrooms;

use App\Models\Building;
use App\Models\Classroom;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\Reactive;
use Livewire\Component;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class Index extends Component
{

    public $classrooms = [];
    public $selectedCampus = null;
    public $selectedBuilding = null;
    public $selectedBuildingId = null;

    public $unit,$department;


    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function mount($campusesAndBuildings = [] )
    {

        $this->unit = Session()->get('unit');
        $this->department = Session()->get('department');

    }

    public function selectCampus($campusName)
    {
        /*
        $this->selectedCampus = $campusName;
        session(['selectedCampus' => $campusName]);

        /*Eğer campuse ait Bina varsa ilk sıradakini seçiyor  ve session kaydediyor yoksa unutuyor
        if (!empty($this->campusesAndBuildings[$campusName])) {
            $this->selectedBuilding = array_key_first($this->campusesAndBuildings[$campusName]);
            session(['selectedBuilding' => $this->selectedBuilding]); // Yeni binayı kaydet
        } else {
            $this->selectedBuilding = null;
            session()->forget('selectedBuilding');
        }
        $this->dispatch('campusSelected', campusName: $campusName);*/
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

        $this->classrooms =
            Classroom::where(function ($query) {
                $query->whereHas('birims', function ($query) {
                    $query->where('birim_id', $this->unit);
                })
                    ->when($this->department && is_numeric($this->department), function ($query) {
                        $query->orWhereHas('bolums', function ($query) {
                            $query->where('bolum_id', $this->department);
                        });
                    });
            })
                ->with(['building.campus', 'birims', 'bolums'])
                ->get() ->groupBy(function ($classroom) {
                    return $classroom->building->campus->name;
                })
                ->map(function ($campusClasses) {
                    return $campusClasses->groupBy(function ($classroom) {
                        return $classroom->building->name;
                    });
                })->toArray();


        return view('livewire.classrooms.dropdown-filter', [
            'campusesAndBuildings' => collect(),
        ]);
    }
}
