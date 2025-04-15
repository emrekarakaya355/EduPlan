<?php

namespace App\Livewire\Classrooms;

use App\Models\Building;
use App\Models\Classroom;
use JetBrains\PhpStorm\NoReturn;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class Index extends Component
{

    public $classrooms = [];
    public $filteredClassrooms = [];
    public $selectedCampus = null;
    public $selectedBuilding = null;

    public $unit,$department;


    protected $listeners = ['filterUpdated' => 'applyFilters'];

    public function mount(): void
    {

        $this->unit = Session()->get('unit');
        $this->department = Session()->get('department');
        $this->selectedCampus = Session()->get('selectedCampus');
        $this->selectedBuilding = Session()->get('selectedBuilding');
        $this->loadClassrooms();
    }
    public $selectedClassroomId = null;
    public $selectedClassroomName = '';

    protected $viewMode = 'classroom';
    public $showClassroomModal = false;

    #[On('open-classroom-modal')]
    public function openClasroomModal($classroomId,$classroomName): void
    {
        $this-> selectedClassroomId = $classroomId;
        $this-> selectedClassroomName = $classroomName;
        $this-> showClassroomModal = true;
    }
    #[On('close-modal')]
    public function closeModal(): void
    {
        $this->showClassroomModal = false;
    }


    public function loadClassrooms(): void
    {
        $this->classrooms = Classroom::where(function ($query) {
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
            ->get()

            ->groupBy(function ($classroom) {
                return $classroom->building->campus->name;
            })

            ->map(function ($campusClasses) {

                $campusClasses->map(function ($classroom) {
                    $classroom->total = $classroom->totalUsageDuration;
                    return $classroom;
                });

                return $campusClasses->groupBy(function ($classroom) {
                    return $classroom->building->name;
                });
            })->toArray();

        $this->filterClassrooms();
     }

    public function applyFilters($filters): void
    {
        if($filters['unit']!=$this->unit) {
            $this->selectedCampus = null;
            $this->selectedBuilding = null;
        }
        $this->unit = $filters['unit'];
        $this->department = $filters['department'];
        $this->loadClassrooms();
    }

    public function updatedSelectedCampus($campusName)
    {
        $this->selectedCampus = $campusName;
        $this->selectedBuilding = null;
        session(['selectedCampus' => $campusName]);
    }

    public function updatedSelectedBuilding($buildingName): void
    {
        $this->selectedBuilding = $buildingName;
        session(['selectedBuilding' => $buildingName]);
        $this->filterClassrooms();
    }
    public function filterClassrooms()
    {
        if ($this->selectedCampus && $this->selectedBuilding) {
            $this->filteredClassrooms = $this->classrooms[$this->selectedCampus][$this->selectedBuilding] ?? [];
        } else {
            $this->filteredClassrooms = [];
        }
    }

    public function render()
    {
        return view('livewire.classrooms.index');
    }
}
