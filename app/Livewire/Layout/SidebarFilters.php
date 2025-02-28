<?php

namespace App\Livewire\Layout;

use App\Models\Birim;
use App\Models\Bolum;
use App\Models\Program;
use Livewire\Component;
use Illuminate\Support\Facades\Session;

class SidebarFilters extends Component
{
    public $units = [];
    public $departments = [];
    public $programs = [];

    public $unit;
    public $department;
    public $program;
    public $year ;
    public $semester ;

    public function mount()
    {
        $this->units = Birim::all()->sortBy('name');
        $this->departments = collect();
        $this->programs = collect();

        $this->unit = Session::get('unit','');
        $this->department = Session::get('department', '');
        $this->program = Session::get('program', '');
        $this->year = Session::get('year', '');
        $this->semester = Session::get('semester', '');
        if ($this->unit) {
            $this->departments = Bolum::where('birim_id', $this->unit)->orderBy('name')->get();
        }

        if ($this->department) {
            $this->programs = Program::where('bolum_id', $this->department)->orderBy('name')->get();
        }
        if(!$this->year){
            $this->setDefaultDate();
        }
    }



    public function updatedUnit($value)
    {
        $this->departments = collect();
        $this->programs = collect();
        Session::forget(['department' ,'program']);
        if($value){
            $this->departments = Bolum::where('birim_id', $value)->get();
            $this->department = '';
            $this->program = '';
        }
    }

    public function updatedDepartment($value)
    {
        $this->programs = collect();
        Session::forget(['program']);
        if($value){
            $this->program='';
            $this->programs = Program::where('bolum_id', $value)->get();
        }

    }

    public function updated($property)
    {
        Session::put($property, $this->$property);
    }

    public function applyFilters()
    {
        $this->dispatch('filterUpdated', [
            'unit'       => $this->unit,
            'department' => $this->department,
            'year'       => $this->year,
            'semester'   => $this->semester,
            'program'    => $this->program,
        ]);
    }

    private function setDefaultDate(): void
    {
        $currentYear = date('Y');
        $currentMonth = date('n');

        if ($currentMonth >= 1 && $currentMonth <= 6) {
            // Ocak - Haziran -> Bahar
            $this->year =  $currentYear - 1 ;
            $this->semester = 'Spring';
        } elseif($currentMonth > 6 && $currentMonth <= 8) {
            // Temmuz - Ağustos -> Yaz
            $this->year = $currentYear;
            $this->semester = 'Summer';
        } else {
            $this->year = $currentYear;
            $this->semester = 'Fall';
        }
        Session::put('year', $this->year);
        Session::put('semester', $this->semester);
    }
}
