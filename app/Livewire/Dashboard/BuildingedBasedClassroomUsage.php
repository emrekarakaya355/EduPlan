<?php

namespace App\Livewire\Dashboard;

use App\Models\Building;
use App\Models\Classroom;
use Asantibanez\LivewireCharts\Models\ColumnChartModel;
use Livewire\Component;

class BuildingedBasedClassroomUsage extends Component
{
    public $selectedBuildingId;
    public $buildings;

    public function mount()
    {
        $this->buildings = Building::all();
    }

    public function render()
    {
        $classrooms = Classroom::withCount(['scheduleSlots'])
            ->where('building_id', $this->selectedBuildingId)
            ->get();
        $chart = (new ColumnChartModel())
            ->setTitle('Sınıf Kullanım Oranları')
            ->setAnimated(true)
            ->setLegendVisibility(false)
            ->setVertical(true)
        ->setXAxisVisible(true)
        ->setColumnWidth('10px');

        foreach ($classrooms as $classroom) {
            $usage = round(($classroom->schedule_slots_count / 50) * 100);
            $chart->addColumn($classroom->name, min($usage, 100), '#3b82f6');
        }

        $columnChart = $chart;
        return view('livewire.dashboard.buildinged-based-classroom-usage',['columnChartModel' => $columnChart]);
    }
}
