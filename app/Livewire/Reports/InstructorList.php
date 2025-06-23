<?php

namespace App\Livewire\Reports;

use App\Models\Course_class;
use App\Models\Instructor;
use App\Models\InstructorConstraint;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class InstructorList extends Component
{
    use WithPagination;

    public $search = '';
    public $instructorId;
    public $sortBy = 'created_at';
    public $sortDirection = 'desc';
    public $perPage = 15;

    public $birimId = null;
    public $bolumId = null;
    public $selectedDays = [];
    public $startTime = '';
    public $endTime = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'sortBy' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
        'birimId' => ['except' => null],
        'bolumId' => ['except' => null],
        'selectedDays' => ['except' => []],
        'startTime' => ['except' => ''],
        'endTime' => ['except' => ''],
    ];

    public function mount()
    {
        $this->resetPage();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    #[On('filters-applied')]
    #[On('instructor-filters-applied')]
    public function onFiltersApplied($reportType, $filters)
    {
        if ($reportType === 'instructor') {
            $this->birimId = $filters['birim_id'] ?? null;
            $this->bolumId = $filters['bolum_id'] ?? null;
            $this->selectedDays = $filters['selected_days'] ?? [];
            $this->startTime = $filters['start_time'] ?? '';
            $this->endTime = $filters['end_time'] ?? '';
            $this->instructorId = $filters['instructor_id'] ?? null;
            $this->resetPage();
        }
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
        $this->birimId = null;
        $this->bolumId = null;
        $this->selectedDays = [];
        $this->startTime = '';
        $this->endTime = '';
        $this->resetPage();
    }

    public function getConstraintsProperty()
    {
        return InstructorConstraint::query()
            ->with(['instructor.courseClasses.program.bolum.birim', 'createdBy', 'updatedBy'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->whereHas('instructor', function ($instructorQuery) {
                        $instructorQuery->where('adi', 'like', '%' . $this->search . '%')
                            ->orWhere('name', 'like', '%' . $this->search . '%')
                            ->orWhere('email', 'like', '%' . $this->search . '%');
                    })
                        ->orWhere('note', 'like', '%' . $this->search . '%')
                        ->orWhereHas('createdBy', function ($creatorQuery) {
                            $creatorQuery->where('adi', 'like', '%' . $this->search . '%')
                                ->orWhere('name', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->when($this->instructorId, function ($query) {
                $query->where('instructor_id', $this->instructorId);
            })
            ->when($this->birimId, function ($query) {
                $query->whereHas('instructor.courseClasses.program.bolum', function ($bolumQuery) {
                    $bolumQuery->where('birim_id', $this->birimId);
                });
            })
            ->when($this->bolumId, function ($query) {
                $query->whereHas('instructor.courseClasses.program', function ($courseQuery) {
                    $courseQuery->where('bolum_id', $this->bolumId);
                });
            })
            ->when(!empty($this->selectedDays), function ($query) {
                // Convert day names to numbers if needed
                $dayNumbers = $this->convertDayNamesToNumbers($this->selectedDays);
                $query->whereIn('day_of_week', $dayNumbers);
            })
            ->when($this->startTime, function ($query) {
                $query->where('start_time', '>=', $this->startTime);
            })
            ->when($this->endTime, function ($query) {
                $query->where('end_time', '<=', $this->endTime);
            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);
    }

    private function convertDayNamesToNumbers($dayNames)
    {
        $dayMapping = [
            'Monday' => 1,
            'Tuesday' => 2,
            'Wednesday' => 3,
            'Thursday' => 4,
            'Friday' => 5,
            'Saturday' => 6,
            'Sunday' => 7,
            'Paz' => 1,
            'Sal' => 2,
            'Çar' => 3,
            'Per' => 4,
            'Cum' => 5,
            'Cmt' => 6,
            'Paz' => 7,
        ];

        $numbers = [];
        foreach ($dayNames as $dayName) {
            if (isset($dayMapping[$dayName])) {
                $numbers[] = $dayMapping[$dayName];
            } elseif (is_numeric($dayName)) {
                $numbers[] = (int)$dayName;
            }
        }

        return $numbers;
    }

    public function getInstructorsProperty()
    {
        return Instructor::query()
            ->with(['courseClasses.program.bolum.birim'])
            ->has('constraints')
            ->when($this->birimId, function ($query) {
                $query->whereHas('courseClasses.program.bolum', function ($bolumQuery) {
                    $bolumQuery->where('birim_id', $this->birimId);
                });
            })
            ->when($this->bolumId, function ($query) {
                $query->whereHas('courseClasses.program', function ($courseQuery) {
                    $courseQuery->where('bolum_id', $this->bolumId);
                });
            })
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

    public function hasActiveFilters()
    {
        return !empty($this->search) ||
            !is_null($this->birimId) ||
            !is_null($this->bolumId) ||
            !empty($this->selectedDays) ||
            !empty($this->startTime) ||
            !empty($this->endTime);
    }

    public function render()
    {
        return view('livewire.reports.instructor-list', [
            'constraints' => $this->constraints,
            'instructors' => $this->instructors,
            'days' => $this->days,
            'hasActiveFilters' => $this->hasActiveFilters(),
        ]);
    }
}
