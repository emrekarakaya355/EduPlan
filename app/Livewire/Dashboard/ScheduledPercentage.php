<?php

namespace App\Livewire\Dashboard;

use App\Models\Course_class;
use App\Models\ScheduleSlot;
use Asantibanez\LivewireCharts\Models\PieChartModel;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class ScheduledPercentage extends Component
{

    public function render()
    {
        $totalScheduledLessons = ScheduleSlot::query()->whereHas('schedule',function ($query){
            return $query->where('year',Session::get('year'))->where('semester',Session::get('semester'));
        })->count();
        $totalLesson = Course_class::query()->whereHas('course',function ($query){
            return $query->where('year',Session::get('year'))->where('semester',Session::get('semester'));
        })->sum('duration');

        $scheduled = $totalScheduledLessons;
        $unscheduled = max($totalLesson - $scheduled, 0);
        $total = $scheduled + $unscheduled;
        $scheduledPercentage = $total > 0 ? round(($scheduled / $total) * 100) : 0;
        $unscheduledPercentage = 100 - $scheduledPercentage;
        $pieChart = (new PieChartModel())
            ->addSlice("Planlanan %{$scheduledPercentage}", $scheduled, '#16a34a')
            ->addSlice("Planlanmamış %{$unscheduledPercentage}", $unscheduled, '#ef4444')
            ->setAnimated(false)
            ->setLegendVisibility(true)
            ->asDonut()
            ->enableShades();

        return view('livewire.dashboard.scheduled-percentage', [
            'pieChartModel' => $pieChart
        ]);
    }
}
