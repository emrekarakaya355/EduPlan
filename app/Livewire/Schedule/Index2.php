<?php

namespace App\Livewire\Schedule;

use App\Models\User;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Ders Programı')]
class Index2 extends Component
{
    public $code;
    public $showCodeInput = false;  // Flag to show or hide the code input field


    public function processCode()
    {
        if ($this->code === '1234') {
            session()->flash('message', 'Code is valid!');
            $this->showCodeInput = false;  // Hide input after successful code entry
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
        return view('livewire.schedule.index');
    }
}
