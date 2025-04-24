<?php

namespace App\Livewire\Instructors;

use App\Models\Birim;
use App\Models\Course_class;
use App\Models\Instructor;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class CompactList extends Component
{

    #[Reactive]
    public $instructors;
    public $unit_name;
    public function mount($unit_id)
    {
        $this->unit_name = Birim::find($unit_id)?->name;
    }

    public function search () {


    }
    public function render()
    {
         return view('livewire.instructors.compact-list');
    }
}
