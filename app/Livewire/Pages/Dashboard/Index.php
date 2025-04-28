<?php

namespace App\Livewire\Pages\Dashboard;

use App\Models\Course_class;
use Livewire\Component;
use Asantibanez\LivewireCharts\Models\PieChartModel;

class Index extends Component
{
    public $unitUnscheduledHours = [];

    public function mount()
    {
        $this->generateChartData();
    }

    public function generateChartData()
    {
        $lessons = Course_class::with(['program.bolum.birim'])
            ->get();

        $groupedByUnit = $lessons->groupBy(function ($lesson) {
            return $lesson->program->bolum->birim->name;
        });

        foreach ($groupedByUnit as $unitName => $lessonsGroup) {
            $totalUnscheduledHours = $lessonsGroup->sum(function ($lesson) {
                return $lesson->unscheduled_hours;
            });

            $this->unitUnscheduledHours[$unitName] = $totalUnscheduledHours;
        }
    }

    public function getRandomColor()
    {
        $colors = ['#3490dc', '#ffed4a', '#e3342f', '#38c172', '#6c757d'];
        return $colors[array_rand($colors)];
    }

    public function render()
    {
        $pieChart = (new PieChartModel())
            ->setTitle('Scheduled Edilmemiş Dersler');

        foreach ($this->unitUnscheduledHours as $unitName => $unscheduledHours) {
            $pieChart->addSlice(
                $unitName,
                $unscheduledHours,
                $this->getRandomColor()
            );
        }
        return view('livewire.pages.dashboard.index', [
            'pieChartModel' => $pieChart
        ]);
    }
}
