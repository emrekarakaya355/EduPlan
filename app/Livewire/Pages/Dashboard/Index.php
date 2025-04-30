<?php

namespace App\Livewire\Pages\Dashboard;

use App\Models\Course_class;
use Asantibanez\LivewireCharts\Models\ColumnChartModel;
use Livewire\Component;
use Asantibanez\LivewireCharts\Models\PieChartModel;

class Index extends Component
{
    public function render()
    {

        return view('livewire.pages.dashboard.index');
    }
}
