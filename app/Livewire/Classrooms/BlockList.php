<?php

namespace App\Livewire\Classrooms;

use Livewire\Attributes\On;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class BlockList extends Component
{
    #[Reactive]
    public $classrooms = [];
    public $showCreateForm = false;

    #[On('toggleForm')]
    public function toggleForm()
    {
        $this->showCreateForm = !$this->showCreateForm;
    }

    public function render()
    {
          return view('livewire.classrooms.block-list');
    }
}
