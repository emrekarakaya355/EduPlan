<?php

namespace App\Livewire\Classrooms;

use App\Models\Building;
use App\Models\Classroom;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

class BlockList extends Component
{
    public $selectedCampus = null;
    public $selectedBuilding = null;

    public $selectedBuildingId = null;

    public $showCreateForm = false;

    public $classrooms = [];

    public function mount()
    {
        if(session('unit')){
                $this->classrooms =Classroom::where(function ($query) {
                    $query->whereHas('birims', function ($query) {
                        $query->where('birim_id', session('unit'));
                    })
                        ->when(session()->has('department') && is_numeric(session('department')), function ($query) {
                            $query->orWhereHas('bolums', function ($query) {
                                $query->where('bolum_id', session('department'));
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
        }
    }

    #[On('filterUpdated')]
    public function classrooms()
    {
        if (!session()->has('unit') || !is_numeric(session('unit'))) {
            return collect();
        }

        return Classroom::where(function ($query) {
            $query->whereHas('birims', function ($query) {
                $query->where('birim_id', session('unit'));
            })
                ->when(session()->has('department') && is_numeric(session('department')), function ($query) {
                    $query->orWhereHas('bolums', function ($query) {
                        $query->where('bolum_id', session('department'));
                    });
                });
        })
            ->with(['building.campus', 'birims', 'bolums'])
            ->get()
            ->groupBy(function ($classroom) {
                return $classroom->building->campus->name;
            })
            ->map(function ($campusClasses) {
                return $campusClasses->groupBy(function ($classroom) {
                    return $classroom->building->name;
                });
            })->toArray();
    }

    #[On('campusSelected')]
    public function updateSelectedCampus($campusName)
    {
        $this->selectedCampus = $campusName;
        $this->selectedBuilding = null; // Reset building when campus changes
    }

    #[On('buildingSelected')]
    public function updateSelectedBuilding($buildingName,$campusName)
    {
        $this->selectedBuilding = $buildingName;
        $this->selectedCampus = $campusName;
        $this->selectedBuildingId = Building::where('name', $buildingName)->first()?->id;
    }

    #[On('toggleForm')]
    public function toggleForm()
    {
        $this->showCreateForm = !$this->showCreateForm;
    }

    public function render()
    {
        return view('livewire.classrooms.block-list', [
            'campusesAndBuildings' => collect(),
        ]);
    }
}
