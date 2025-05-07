<?php

namespace App\Livewire\Layout;

use Livewire\Component;

class ReportFilters extends Component
{
    public $reportTypes = [
        'course' => 'Ders',
        'instructor' => 'Öğretim Görevlisi',
        'classroom' => 'Sınıf',
    ];
    public $selectedReportType;

    public function mount() {
        $this->selectedReportType = session('selectedReportType', $this->selectedReportType ?? 'classroom');
    }

    public function updatedSelectedReportType($reportType){
        $this->selectedReportType = $reportType;
        session()->put('selectedReportType', $this->selectedReportType);
        $this->dispatch('report-type-changed', reportType: $this->selectedReportType,);
    }

    public function render()
    {
         return view('livewire.layout.report-filters');
    }
}
