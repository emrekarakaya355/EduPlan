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
    public $program; // Eğer seçilen program için tek değer kullanacaksan
    public $year;
    public $semester;

    protected $listeners = ['resetFilters' => 'resetFilterValues'];

    public function mount()
    {
        // Veritabanından seçenekleri çek
        $this->units = Birim::all();
        $this->departments = collect(); // Başlangıçta boş koleksiyon
        $this->programs = collect();    // Başlangıçta boş koleksiyon

        // Session'dan filtreleri yükle
        $this->unit = Session::get('unit', '');
        $this->department = Session::get('department', '');
        $this->program = Session::get('program', '');
        $this->year = Session::get('year', '');
        $this->semester = Session::get('semester', '');

        // Eğer unit seçiliyse, ilgili bölümleri yükle
        if ($this->unit) {
            $this->departments = Bolum::where('birim_id', $this->unit)->get();
        }

        // Eğer bölüm seçiliyse, ilgili programları yükle
        if ($this->department) {
            $this->programs = Program::where('bolum_id', $this->department)->get();
        }
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
            $this->programs = Program::where('bolum_id', $value)->get();
        }

    }

    public function updated($property)
    {
        // Her değişiklikte seçilen değeri session'a kaydet
        Session::put($property, $this->$property);
    }

    public function applyFilters()
    {

        // Filtrele butonuna basıldığında mevcut filtreleri event ile gönder
        $this->dispatch('filterUpdated', [
            'unit'       => $this->unit,
            'department' => $this->department,
            'year'       => $this->year,
            'semester'   => $this->semester,
            'program'    => $this->program, // Eğer program seçimi varsa
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
