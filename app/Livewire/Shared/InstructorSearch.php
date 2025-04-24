<?php

namespace App\Livewire\Shared;

use App\Models\Instructor;
use Livewire\Component;

class InstructorSearch extends Component
{

    public $search = '';

    public function render()
    {
        $instructors = collect();

        if (strlen($this->search) > 1) {
            $search = strtolower($this->search);

            $instructors = Instructor::query()
                ->whereRaw("LOWER(CONCAT(adi, ' ', soyadi)) LIKE ?", ["%{$search}%"])
                ->orWhereRaw("LOWER(adi) LIKE ?", ["%{$search}%"])
                ->orWhereRaw("LOWER(soyadi) LIKE ?", ["%{$search}%"])
                ->get();
        }
        return view('livewire.shared.instructor-search', compact('instructors'));
    }
}
