<?php

namespace App\Livewire;

use Livewire\Attributes\On;
use Livewire\Component;

class DynamicDetail extends Component
{
    public $detailData = [];
    #[On('showDetail')]
    public function showDetail($model, $id)
    {
        $class = "App\\Models\\$model";

        if (class_exists($class)) {
            $instance = $class::find($id);
            if ($instance) {
                $this->detailData = $instance->getDetailColumns() ?? [];
            }
        }
    }
    public function render()
    {
        return view('livewire.dynamic-detail');
    }
}
