<?php

namespace App\Livewire\Settings;

use App\Models\Instructor;
use Livewire\Component;

class InstructorList extends Component
{
    public $instructors;
    public function mount(){
        $this->instructors = Instructor::all();
    }
    public function render()
    {
        return view('livewire..settings.instructor-list');
    }
}
