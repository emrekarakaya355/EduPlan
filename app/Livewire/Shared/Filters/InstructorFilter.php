<?php

namespace App\Livewire\Shared\Filters;

use App\Models\Birim;
use App\Models\Bolum;
use App\Models\Instructor;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class InstructorFilter extends Component
{
    public $search;
    public $searchResults = [];
    public $selectedInstructor = null;
    public $selectedInstructorName = '';
    public $showSearchResults = false;


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
    public function updatedSearch()
    {
        if (strlen($this->search) >= 2) {
            $this->searchInstructors();
            $this->showSearchResults = true;
        } else {
            $this->searchResults = [];
            $this->showSearchResults = false;
        }
    }
    public function searchInstructors()
    {
         $searchTerm = '%' . $this->search . '%';
        $this->searchResults = Instructor::query()
            ->whereHas('courseClasses')
            ->where(function ($query) use ($searchTerm) {
                $query->where('adi', 'ILIKE', $searchTerm)
                    ->orWhere('soyadi', 'ILIKE', $searchTerm)
                    ->orWhere('email', 'ILIKE', $searchTerm)
                    ->orWhereRaw("CONCAT(adi, ' ', soyadi) ILIKE ?", [$searchTerm])
                    ->orWhereRaw("CONCAT(soyadi, ' ', adi) ILIKE ?", [$searchTerm]);
            })
            ->limit(10)
            ->get();

    }
    public function selectInstructor($instructorId, $instructorName)
    {
        $this->selectedInstructor = $instructorId;
        $this->selectedInstructorName = $instructorName;
        $this->search = $instructorName;
        $this->showSearchResults = false;
        $this->searchResults = [];
        $this->dispatch('instructor-filters-applied', reportType: 'instructor', filters: ['instructor_id' => $this->selectedInstructor]);

    }
    public function clearInstructorSelection()
    {
        $this->selectedInstructor = null;
        $this->selectedInstructorName = '';
        $this->search = '';
        $this->showSearchResults = false;
        $this->searchResults = [];
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
            'instructor_id' => $this->selectedInstructor,

        ];
        $this->dispatch('filters-applied', reportType: 'instructor', filters: $filterData);
    }

    public function resetFilters()
    {
        $this->selectedBirim = null;
        $this->selectedBolum= null;
        $this->selectedDays = [];
        $this->startTime = "";
        $this->endTime = "";
        $this->selectedInstructor = null;

        $filterData = [
            'birim_id' => $this->selectedBirim,
            'bolum_id' => $this->selectedBolum,
            'selected_days' => $this->selectedDays,
            'start_time' => $this->startTime,
            'end_time' => $this->endTime,
            'instructor_id' => $this->selectedInstructor,

        ];
        $this->dispatch('filters-applied', reportType: 'instructor', filters: $filterData);
        $this->clearInstructorSelection();
    }


    public function render()
    {
        return view('livewire.shared.filters.instructor-filter');
    }
}
