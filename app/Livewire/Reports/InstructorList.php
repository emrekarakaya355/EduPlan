<?php

namespace App\Livewire\Reports;

use App\Models\Course_class;
use App\Models\Instructor;
use App\Models\InstructorConstraint;
use Livewire\Component;
use Livewire\WithPagination;

class InstructorList extends Component
{
    use WithPagination;

    public $search = '';
    public $instructorFilter = '';
    public $dayFilter = '';
    public $sortBy = 'created_at';
    public $sortDirection = 'desc';
    public $perPage = 15;

    protected $queryString = [
        'search' => ['except' => ''],
        'instructorFilter' => ['except' => ''],
        'dayFilter' => ['except' => ''],
        'sortBy' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
    ];

    public function mount()
    {
        $this->resetPage();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingInstructorFilter()
    {
        $this->resetPage();
    }

    public function updatingDayFilter()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->instructorFilter = '';
        $this->dayFilter = '';
        $this->resetPage();
    }

    public function getConstraintsProperty()
    {
        return InstructorConstraint::query()
            ->with(['instructor', 'createdBy', 'updatedBy'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->whereHas('instructor', function ($instructorQuery) {
                        $instructorQuery->where('adi', 'like', '%' . $this->search . '%')
                            ->orWhere('email', 'like', '%' . $this->search . '%');
                    })
                        ->orWhere('note', 'like', '%' . $this->search . '%')
                        ->orWhereHas('createdBy', function ($creatorQuery) {
                            $creatorQuery->where('adi', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->when($this->instructorFilter, function ($query) {
                $query->where('instructor_id', $this->instructorFilter);
            })
            ->when($this->dayFilter, function ($query) {
                $query->where('day_of_week', $this->dayFilter);
            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);
    }

    public function getInstructorsProperty()
    {
        return Instructor::has('constraints')
            ->orderBy('adi')
            ->get();
    }

    public function getDaysProperty()
    {
        return [
            1 => 'Pazartesi',
            2 => 'Salı',
            3 => 'Çarşamba',
            4 => 'Perşembe',
            5 => 'Cuma',
            6 => 'Cumartesi',
            7 => 'Pazar'
        ];
    }

    public function getDayName($dayNumber)
    {
        return $this->days[$dayNumber] ?? 'Bilinmiyor';
    }

    public function render()
    {
        return view('livewire.reports.instructor-list', [
            'constraints' => $this->constraints,
            'instructors' => $this->instructors,
            'days' => $this->days,
        ]);
    }

}
