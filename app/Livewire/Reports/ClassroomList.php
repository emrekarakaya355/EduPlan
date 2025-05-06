<?php

namespace App\Livewire\Reports;

use App\Models\Classroom;
use Livewire\Attributes\On;
use Livewire\Component;

class ClassroomList extends Component
{
    public $classrooms = [];

    #[On('filters-applied')]
    public function loadData($filters)
    {
        $query = Classroom::query();
        dd($filters);
        if ($filters['campus_id']) {
            $query->where('campus_id', $filters['campus_id']);
        }

        if ($filters['building_id']) {
            $query->where('building_id', $filters['building_id']);
        }

        if ($filters['classroom_type_id']) {
            $query->where('classroom_type_id', $filters['classroom_type_id']);
        }

        if ($filters['min_capacity']) {
            $query->where('capacity', '>=', $filters['min_capacity']);
        }

        if ($filters['max_capacity']) {
            $query->where('capacity', '<=', $filters['max_capacity']);
        }

        if ($filters['min_exam_capacity']) {
            $query->where('exam_capacity', '>=', $filters['min_exam_capacity']);
        }

        if ($filters['max_exam_capacity']) {
            $query->where('exam_capacity', '<=', $filters['max_exam_capacity']);
        }

        if (!is_null($filters['is_active'])) {
            $query->where('is_active', $filters['is_active']);
        }

        $this->classrooms = $query->get()->toArray();
    }


    public function render()
    {
        return view('livewire.reports.classroom-list');
    }
}
