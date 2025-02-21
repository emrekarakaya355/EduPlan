<?php

namespace App\Livewire\Layout;

use App\Models\Birim;
use App\Models\Bolum;
use App\Models\Program;
use Livewire\Component;
use Illuminate\Support\Facades\Session;

class SidebarFilters extends Component
{
    public $units;
    public $departments = [];
    public $programs = [];

    public $unit;
    public $department;
    public $program;
    public $year ;
    public $semester ;

    protected $listeners = ['resetFilters' => 'resetFilterValues'];

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

        if(!$this->unit){
            $this->unit = $this->units->first()?->id ?? null;
            Session::put('unit', $this->unit);

        }
        if ($this->unit) {
            $this->departments = Bolum::where('birim_id', $this->unit)->get();
        }
        if(!$this->department){
            $this->department = $this->departments?->first()?->id ?? null;
            Session::put('department', $this->department);

        }
        if ($this->department) {
            $this->programs = Program::where('bolum_id', $this->department)->get();
        }
        if (!$this->program) {
            $this->program = $this->programs->first()?->id ?? null;
            Session::put('program', $this->program);
        }
        if(!$this->year){
            $this->defaultDate();
        }
    }

    private function defaultDate(){
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

    public function updatedUnit($value)
    {
        $this->departments = collect();
        $this->programs = collect();
        if($value){
            // Unit seçildiğinde, ilgili bölümleri yükle
            $this->departments = Bolum::where('birim_id', $value)->get();
            $this->department = ''; // Eski bölümü sıfırla
            $this->programs = collect(); // Programları sıfırla
        }
    }

    public function updatedDepartment($value)
    {
        $this->programs = collect();
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

    public function resetFilterValues()
    {
        $this->unit = '';
        $this->department = '';
        $this->year = '';
        $this->semester = '';
        $this->program = '';

        Session::forget(['unit', 'department', 'year', 'semester', 'program']);
        $this->dispatch('filterUpdated', []);
    }

    public function render()
    {
        return view('livewire.layout.sidebar-filters');
    }
}
