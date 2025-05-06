<?php

namespace App\Livewire\Shared\Filters;

use App\Models\Building;
use App\Models\Campus;
use Livewire\Component;

class ClassroomFilter extends Component
{
    public $campuses = [];
    public $buildings = [];
    public $classroomTypes = [];

    public $selectedCampus = null;
    public $selectedBuilding = null;
    public $selectedClassroomType = null;
    public $minCapacity = null;
    public $maxCapacity = null;
    public $minExamCapacity= null;
    public $maxExamCapacity= null;
    public $isActive = true;
    public $isFull= false;

    public function mount()
    {
        $this->loadCampuses();
        $this->loadClassroomTypes();
    }

    public function updatedSelectedCampus()
    {
        $this->selectedBuilding = null;
        $this->buildings = [];

        if ($this->selectedCampus) {
            $this->loadBuildings();
        }
    }

    private function loadCampuses()
    {
        $this->campuses = Campus::all();
    }

    private function loadBuildings()
    {
        // Seçili kampüse göre binaları getir
        $this->buildings = Building::where('campus_id', $this->selectedCampus)
            ->orderBy('name')
            ->get()
            ->toArray();
    }

    private function loadClassroomTypes()
    {
        $this->classroomTypes = ['Laboratuar', 'Sınıf', 'Atölye', 'Salon', 'Ö.Ü. Odası', 'Seminer Odası', 'Anfi'];
    }

    public function applyFilters()
    {

        $filterData = [
            'campus_id' => $this->selectedCampus,
            'building_id' => $this->selectedBuilding,
            'classroom_type_id' => $this->selectedClassroomType,
            'min_capacity' => $this->minCapacity,
            'max_capacity' => $this->maxCapacity,
            'min_exam_capacity' => $this->minExamCapacity,
            'max_exam_capacity' => $this->maxExamCapacity,
            'is_active' => $this->isActive,
            'is_full' => $this->isFull,
        ];
        $this->dispatch('filters-applied', reportType: 'classroom', filters: $filterData);
    }

    public function resetFilters()
    {
        $this->selectedCampus = null;
        $this->selectedBuilding = null;
        $this->selectedClassroomType = null;
        $this->minCapacity = null;
        $this->maxCapacity = null;
        $this->minExamCapacity = null;
        $this->maxExamCapacity = null;
        $this->isActive = true;
        $this->isFull = false;
        $this->buildings = [];
    }

    public function render()
    {
        return view('livewire.shared.filters.classroom-filter');
    }
}
