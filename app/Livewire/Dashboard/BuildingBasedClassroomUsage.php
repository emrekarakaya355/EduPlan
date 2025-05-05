<?php

namespace App\Livewire\Dashboard;

use App\Models\Building;
use App\Models\Classroom;
use Livewire\Component;

class BuildingBasedClassroomUsage extends Component
{
    public $selectedBuildingId;
    public $buildings;
    public $chartData = [];

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

    private function prepareChartData()
    {
        $classrooms = Classroom::withCount(['scheduleSlots'])
            ->where('building_id', $this->selectedBuildingId)
            ->get();
        $building = $this->buildings->find($this->selectedBuildingId);
        $labels = [];
        $data = [];
        $colors = [];

        foreach ($classrooms as $classroom) {
            $usage = round(($classroom->schedule_slots_count / 50) * 100);
            $usage = min($usage, 100);

            $labels[] = $classroom->name;
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
            'title' => $building?->name
        ];

        $this->dispatch('classroomChartDataUpdated', chartData: $this->chartData);
    }

    public function render()
    {
        return view('livewire.dashboard.building-based-classroom-usage');
    }
}
