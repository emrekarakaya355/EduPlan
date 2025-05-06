<?php

namespace App\Livewire\Dashboard;

use App\Models\Building;
use Livewire\Component;

class ClassroomUsagePerBuildingApex extends Component
{
    public $buildingUsage;
    public $chartOptions;
    public $chartSeries;

    public function mount()
    {
        $this->generateChartData();
        $this->prepareChartOptions();
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

                $totalUsedSlots += $usedSlots;
                $totalCapacitySlots += $classroomCapacity;
            }

            $buildingUsagePercentage = $totalCapacitySlots ? ($totalUsedSlots / $totalCapacitySlots) * 100 : 0;

            return [
                'name' => $building->name,
                'usage_percentage' => round($buildingUsagePercentage, 1),
            ];
        });
    }

    public function prepareChartOptions()
    {
        $buildingNames = $this->buildingUsage->pluck('name')->toArray();
        $usagePercentages = $this->buildingUsage->pluck('usage_percentage')->toArray();

        $this->chartSeries = [
            [
                'name' => 'Kullanım Yüzdesi',
                'data' => $usagePercentages,
            ]
        ];

        $this->chartOptions = [
            'chart' => [
                'type' => 'bar',
                'height' => 550,
                'toolbar' => [
                    'show' => true,
                ],
            ],
            'plotOptions' => [
                'bar' => [
                    'horizontal' => true,
                    'distributed' => true,
                    'dataLabels' => [
                        'position' => 'top',
                    ],
                    'barHeight' => '100%',
                ],
            ],
            'colors' => $this->getChartColors(),
            'dataLabels' => [
                'enabled' => true,
                'formatter' => 'function(val) { return val + "%" }',
                'offsetX' => 0,
                'style' => [
                    'fontSize' => '12px',
                    'fontWeight' => 'bold',
                ],
            ],
            'stroke' => [
                'width' => 1,
                'colors' => ['#fff'],
            ],
            'title' => [
                'text' => 'Binaların Kullanım Yüzdesi',
                'align' => 'center',
            ],
            'xaxis' => [
                'categories' => $buildingNames,
                'labels' => [
                    'style' => [
                        'fontSize' => '12px',
                    ],
                ],
                'max' => 100,
            ],
            'yaxis' => [
                'min' => 0,
                'max' => 100,
                'labels' => [
                    'formatter' => 'function(val) { return val + "%" }',
                    'style' => [
                        'fontSize' => '10px',
                    ],
                ],
            ],
            'tooltip' => [
                'theme' => 'dark',
                'y' => [
                    'formatter' => 'function(val) { return val + "%" }',
                ],
            ],
            'grid' => [
                'borderColor' => '#e0e0e0',
                'strokeDashArray' => 1,
            ],
            'legend' => [
                'show' => false,
            ],
        ];
    }

    public function getChartColors()
    {
        return ['#3490dc', '#ffed4a', '#e3342f', '#38c172', '#6c757d', '#f66d9b', '#6cb2eb', '#9561e2', '#f6993f', '#4dc0b5'];
    }

    public function render()
    {
        return view('livewire.dashboard.classroom-usage-per-building-apex');
    }
}
