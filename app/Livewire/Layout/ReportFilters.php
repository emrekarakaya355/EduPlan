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

    protected $queryString = [
        'selectedReportType' => ['except' => '']
    ];

    public function updatedSelectedReportType($reportType){
        $this->selectedReportType = $reportType;
        $this->dispatch('report-type-changed', reportType: $this->selectedReportType,);


    }

    public function render()
    {
         return view('livewire.layout.report-filters');
    }
}
