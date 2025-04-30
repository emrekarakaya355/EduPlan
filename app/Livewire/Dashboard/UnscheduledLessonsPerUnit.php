<?php

namespace App\Livewire\Dashboard;

use App\Models\Course_class;
use Asantibanez\LivewireCharts\Models\ColumnChartModel;
use Livewire\Attributes\Lazy;
use Livewire\Component;

#[Lazy]
class UnscheduledLessonsPerUnit extends Component
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
            return $lesson->program->bolum->birim->display_name;
        });

        foreach ($groupedByUnit as $unitName => $lessonsGroup) {
            $totalUnscheduledHours = $lessonsGroup->sum(function ($lesson) {
                return $lesson->unscheduled_hours;
            });

            $this->unitUnscheduledHours[$unitName] = $totalUnscheduledHours;
        }
        arsort($this->unitUnscheduledHours);

    }

    public function getRandomColor()
    {
        $colors = ['#3490dc', '#ffed4a', '#e3342f', '#38c172', '#6c757d'];
        return $colors[array_rand($colors)];
    }

    public function render()
    {
        $columnChart = (new ColumnChartModel())
            ->setAnimated(true)
            ->setLegendVisibility(false)
            ->setSmoothCurve()
            ->setColumnWidth('5px')
            ->setTitle('Saati Belirlenmemiş Ders Saati')
            ;


        foreach ($this->unitUnscheduledHours as $unitName => $unscheduledHours) {
            $columnChart->addColumn(
                $unitName,
                $unscheduledHours,
                $this->getRandomColor()
            );
        }
        return view('livewire.dashboard.unscheduled-lessons-per-unit', [
            'columnChartModel' => $columnChart
        ]);
    }
}
