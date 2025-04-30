<?php

namespace App\Livewire\Dashboard;

use App\Models\Building;
use App\Models\Classroom;
use Asantibanez\LivewireCharts\Models\ColumnChartModel;
use Livewire\Component;

class ClassroomUsagePerBuilding extends Component
{

    public $buildingUsage;

    public function mount()
    {
        $this->generateChartData();
    }

    public function generateChartData()
    {
        $buildings = Building::with('classrooms.scheduleSlots')->get();

        $this->buildingUsage = $buildings->map(function ($building) {
            $totalUsedSlots = 0;
            $totalCapacitySlots = 0;

            foreach ($building->classrooms as $classroom) {
                $usedSlots = $classroom->scheduleSlots->count();

                $classroomCapacity = 50;

                $usagePercentage = $usedSlots / $classroomCapacity * 100;

                $totalUsedSlots += $usedSlots;
                $totalCapacitySlots += $classroomCapacity;
            }

            $buildingUsagePercentage = $totalCapacitySlots ? ($totalUsedSlots / $totalCapacitySlots) * 100 : 0;

            return [
                'name' => $building->name,
                'usage_percentage' => $buildingUsagePercentage,
            ];
        });



    }
    public function getRandomColor()
    {
        $colors = ['#3490dc', '#ffed4a', '#e3342f', '#38c172', '#6c757d'];
        return $colors[array_rand($colors)];
    }
    public function render()
    {

        $columnChart = (new ColumnChartModel())
            ->setTitle('Sınıfların Kullanım Yüzdesi')
            ->setAnimated(true)
            ->setLegendVisibility(false)
            ->setColumnWidth('5px')
            ->setTitle('Binaların Kullanım Yüzdesi')
            ->setSmoothCurve()
            ->setHorizontal()
            ->stacked()
             ;

        foreach ($this->buildingUsage as $building) {
            $columnChart->addColumn(
                $building['name'],
                $building['usage_percentage'],
                $this->getRandomColor()
            );
        }

        return view('livewire.dashboard.classroom-usage-per-building',['columnChartModel' => $columnChart]);
    }
}
