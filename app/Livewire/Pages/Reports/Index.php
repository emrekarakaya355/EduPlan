<?php

namespace App\Livewire\Pages\Reports;

use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Raporlar')]
class Index extends Component
{
    public $selectedReportType;

    public function mount(){
        $this->selectedReportType = session('selectedReportType', $this->selectedReportType ?? 'classroom');
    }

    #[On('report-type-changed')]
    public function applyFilter($reportType)
    {
        $this->selectedReportType = $reportType;
    }

    public function render()
    {
        return view('livewire.pages.reports.index');
    }
}
