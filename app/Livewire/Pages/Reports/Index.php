<?php

namespace App\Livewire\Pages\Reports;

use Livewire\Attributes\On;
use Livewire\Component;

class Index extends Component
{
    public $reportType;
    #[On('report-type-changed')]
    public function applyFilter($reportType)
    {
        $this->reportType = $reportType;
    }

    public function render()
    {
        return view('livewire.pages.reports.index');
    }
}
