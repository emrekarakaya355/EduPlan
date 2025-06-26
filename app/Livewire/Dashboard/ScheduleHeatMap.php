<?php

namespace App\Livewire\Dashboard;

use App\Models\ScheduleSlot;
use Carbon\Carbon;
use Livewire\Component;

class ScheduleHeatMap extends Component
{
    public $heatmapData = [];
    public $categories = [];

    public function mount()
    {
        $this->prepareHeatmapData();
    }

    public function prepareHeatmapData()
    {
        $slots = ScheduleSlot::all();

        $days = [
            1 => 'Pazartesi',
            2 => 'Salı',
            3 => 'Çarşamba',
            4 => 'Perşembe',
            5 => 'Cuma'
        ];

        $hours = range(8, 24);
        $this->categories = array_map(fn($h) => "$h:00", $hours);

        $data = [];
        foreach ($days as $day => $dayName) {
            $data[$dayName] = array_fill_keys($hours, 0);
        }

        foreach ($slots as $slot) {
            if (!isset($days[$slot->day])) continue;

            $dayName = $days[$slot->day];
            $hour = Carbon::parse($slot->start_time)->hour;

            if (isset($data[$dayName][$hour])) {
                $data[$dayName][$hour]++;
            }
        }

        $result = [];
        foreach ($data as $day => $hourly) {
            $row = [
                'name' => $day,
                'data' => []
            ];
            foreach ($hourly as $hour => $count) {
                $row['data'][] = [
                    'x' => "$hour:00",
                    'y' => $count
                ];
            }
            $result[] = $row;
        }
        $this->heatmapData = $result;
    }

    public function render()
    {
        return view('livewire.dashboard.schedule-heat-map');
    }
}
