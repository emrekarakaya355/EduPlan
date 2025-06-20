<?php

namespace App\Livewire\Shared\Filters;

use App\Models\Birim;
use App\Models\Bolum;
use Livewire\Component;

class InstructorFilter extends Component
{
    public $search;
    public $birims = [];
    public $bolums= [];
    public $days = [];

    public $selectedBirim = null;
    public $selectedBolum = null;
    public $selectedDays = [];
    public $startTime = "";
    public $endTime = "";
    public $showAdvancedSearch = false;

    public function mount()
    {
        $this->loadBirims();
        $this->loadDays();

    }

    public function updatedSelectedBirim()
    {
        $this->selectedBolum= null;
        $this->bolums = [];

        if ($this->selectedBirim) {
            $this->loadBolums();
        }
    }

    private function loadBirims()
    {
        $this->birims = Birim::query()->orderBy('name')->get();
    }

    private function loadBolums()
    {
        $this->bolums = Bolum::where('birim_id', $this->selectedBirim)
            ->orderBy('name')
            ->get()
            ->toArray();
    }


    private function loadDays()
    {
        $this->days = [
            'Monday' => 'Paz',
            'Tuesday' => 'Sal',
            'Wednesday' => 'Çar',
            'Thursday' => 'Per',
            'Friday' => 'Cum',
        ];
    }

    public function applyFilters()
    {

        $filterData = [
            'birim_id' => $this->selectedBirim,
            'bolum_id' => $this->selectedBolum,
            'selected_days' => $this->selectedDays,
            'start_time' => $this->startTime,
            'end_time' => $this->endTime,

        ];
        $this->dispatch('filters-applied', reportType: 'classroom', filters: $filterData);
    }

    public function resetFilters()
    {
        $this->selectedBirim = null;
        $this->selectedBolum= null;
        $this->selectedDays = [];
        $this->startTime = "";
        $this->endTime = "";
    }


    public function render()
    {
        return view('livewire.shared.filters.instructor-filter');
    }
}
