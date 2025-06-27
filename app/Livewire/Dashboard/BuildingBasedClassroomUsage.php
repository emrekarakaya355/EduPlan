<?php

namespace App\Livewire\Dashboard;

use App\Models\Building;
use App\Models\Classroom;
use Livewire\Attributes\On;
use Livewire\Component;

class BuildingBasedClassroomUsage extends Component
{
    public $selectedBuildingId;
    public $buildings;
    public $chartData = [];

    public $selectedClassroomId;
    public $selectedClassroomName;
    public $showClassroomModal = false;


    public function mount()
    {
        $this->buildings = Building::all();
        $this->selectedBuildingId = $this->buildings?->first()?->id;

        if ($this->selectedBuildingId) {
            $this->prepareChartData();
        }
    }

    public function updatedSelectedBuildingId()
    {
        $this->prepareChartData();
    }


    #[On('classroomShow')]
    public function openClassroomSchedule($classroomId,$classroomName)
    {
        $this->selectedClassroomId = $classroomId;
        $this->selectedClassroomName = $classroomName;
        $this->showClassroomModal = true;
    }
    #[On('close-modal')]
    public function closeClassroom()
    {
        $this->showClassroomModal = false;
    }
    #[On('buildingSelectedFromApexChart')]
    public function buildingSelected($buildingId){
        $this->selectedBuildingId = $buildingId;
        $this->prepareChartData();
    }
    private function prepareChartData()
    {
        $classrooms = Classroom::where('building_id', $this->selectedBuildingId)
            ->get();
        $building = $this->buildings->find($this->selectedBuildingId);
        $labels = [];
        $data = [];
        $colors = [];
        $ids = [];
        foreach ($classrooms as $classroom) {
            $usage = round(($classroom->UniqueUsedTimeSlotsCount / 45) * 100);

            $usage = min($usage, 100);

            $labels[] = $classroom->name;
            $ids[] = $classroom->id;
            $data[] = $usage;

            if ($usage < 30) {
                $colors[] = '#10b981';
            } elseif ($usage < 70) {
                $colors[] = '#f59e0b';
            } else {
                $colors[] = '#ef4444';
            }
        }
        $this->chartData = [
            'labels' => $labels,
            'data' => $data,
            'colors' => $colors,
            'title' => $building?->name,
            'classroomIds' => $ids,
        ];

        $this->dispatch('classroomChartDataUpdated', chartData: $this->chartData);
    }

    public function render()
    {
        return view('livewire.dashboard.building-based-classroom-usage');
    }
}
