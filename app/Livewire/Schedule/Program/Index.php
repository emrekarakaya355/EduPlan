<?php

namespace App\Livewire\Schedule\Program;

use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Ders Programı')]
class Index extends Component
{
    public $code;
    public $showCodeInput = false;

    public function processCode()
    {
        if ($this->code === '1234') {
            session()->flash('message', 'Code is valid!');
            $this->showCodeInput = false;
        } else {
            session()->flash('error', 'Invalid code!');
        }
    }
    public function mount()
    {
/*
                if(auth()->user()->id == 13284) {
                    $this->dispatch('show-confirmx', [
                        'message' => ' Bir Yemek ısmarlarsanız kodu sms ile gönderiyorum. :):):):)',
                        'type' => 'error'
                    ]);
               }*/
    }
    public function render()
    {
        return view('livewire.schedule.program.index');
    }
}
