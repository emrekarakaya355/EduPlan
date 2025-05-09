<?php

namespace App\Livewire\Shared\Filters;

use App\Models\Building;
use App\Models\Campus;
use Livewire\Component;

class ClassroomFilter extends Component
{
    public $campuses = [];
    public $buildings = [];
    public $days = [];
    public $classroomTypes = [];

    public $selectedCampus = null;
    public $selectedBuilding = null;
    public $selectedClassroomType = null;
    public $minCapacity = null;
    public $maxCapacity = null;
    public $minExamCapacity= null;
    public $maxExamCapacity= null;
    public $isActive = true;
    public $showAvailable= "";

    public $selectedDays = [];
    public $startTime = "";
    public $endTime = "";

    public $showAdvancedSearch = false;

    public function mount()
    {
        $this->loadCampuses();
        $this->loadClassroomTypes();
        $this->loadDays();

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
    private function loadDays()
    {
        $this->days = [
            'Monday' => 'Paz',
            'Tuesday' => 'Sal',
            'Wednesday' => 'Çar',
            'Thursday' => 'Per',
            'Friday' => 'Cum',
        ];
    }

    public function applyFilters()
    {

        $filterData = [
            'campus_id' => $this->selectedCampus,
            'building_id' => $this->selectedBuilding,
            'classroom_type' => $this->selectedClassroomType,
            'min_capacity' => $this->minCapacity,
            'max_capacity' => $this->maxCapacity,
            'min_exam_capacity' => $this->minExamCapacity,
            'max_exam_capacity' => $this->maxExamCapacity,
            'is_active' => $this->isActive,
            'show_available' => $this->showAvailable,
            'selected_days' => $this->selectedDays,
            'start_time' => $this->startTime,
            'end_time' => $this->endTime,

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
        $this->showAvailable = "";
        $this->buildings = [];
        $this->selectedDays = [];
        $this->startTime = "";
        $this->endTime = "";
    }

    public function render()
    {
        return view('livewire.shared.filters.classroom-filter');
    }
}
