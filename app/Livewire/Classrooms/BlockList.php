<?php

namespace App\Livewire\Classrooms;

use App\Models\Birim;
use App\Models\Classroom;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class BlockList extends Component
{

    public $isBirim = true;
    public $selectedCampus = null;
    public $selectedBuilding = null;
    protected $listeners = ['filterUpdated' => 'classrooms'];

    #[On('filterUpdated')]
    #[Computed]
    public function classrooms()
    {

        return Classroom::whereHas('birims', function($query) {
            $query->where('birim_id', session('unit'));
        })
            ->with(['building.campus', 'birims'])
            ->get()
            ->groupBy(function ($classroom) {
                return $classroom->building->campus->name;
            })
            ->map(function ($campusClasses) {
                return $campusClasses->groupBy(function ($classroom) {
                    return $classroom->building->name;
                });
            });

    }
    #[On('filterUpdated')]
    #[Computed]
    public function bolumClassrooms()
    {
        return Classroom::whereHas('bolums', function($query) {
            $query->where('bolum_id', session('department'));
        })
            ->with(['building.campus', 'bolums'])
            ->get()
            ->groupBy(function ($classroom) {
                return $classroom->building->campus->name;
            })
            ->map(function ($campusClasses) {
                return $campusClasses->groupBy(function ($classroom) {
                    return $classroom->building->name;
                });
            });

    }
    public function render()
    {

          return view('livewire.classrooms.block-list');
    }
}
