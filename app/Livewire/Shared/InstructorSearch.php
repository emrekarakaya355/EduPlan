<?php
namespace App\Livewire\Shared;

use App\Models\Instructor;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Livewire\Attributes\On;

class InstructorSearch extends Component
{
    public $search = '';


    public function updatedSearch()
    {
        Session::forget('selectedCampusId');
        Session::forget('selectedBuildingId');
        if (empty($this->search)) {
            //$this->dispatch('instructorSelected', ['id' => null,'name'=>""]);
        }
    }

    public function render()
    {
        $instructors = collect();

        if (strlen($this->search) > 1) {
            $searchLower = strtolower($this->search);

            $instructors = Instructor::query()
                ->whereRaw("LOWER(CONCAT(adi, ' ', soyadi)) LIKE ?", ["%{$searchLower}%"])
                ->orWhereRaw("LOWER(adi) LIKE ?", ["%{$searchLower}%"])
                ->orWhereRaw("LOWER(soyadi) LIKE ?", ["%{$searchLower}%"])
                ->get();
        }
        return view('livewire.shared.instructor-search', compact('instructors'));
    }
}
