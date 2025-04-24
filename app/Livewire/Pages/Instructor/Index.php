<?php

namespace App\Livewire\Pages\Instructor;
use App\Models\Course_class;
use App\Models\Instructor;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\On;
use Livewire\Component;

class Index extends Component
{
    public $unit_id, $year, $semester,$instructors;
    public $selectedInstructorId ;
    public $selectedInstructorName;
    protected $listeners = ['filterUpdated' => 'applyFilters'];

    public function mount()
    {
        $this->unit_id = Session::get('unit');
        $this->year = Session::get('year');
        $this->semester = Session::get('semester');
        $this->loadInstructors();
    }

    public function applyFilters($filters)
    {
        $this->unit_id = $filters['unit'];
        $this->year = $filters['year'];
        $this->semester = $filters['semester'];
        $this->loadInstructors();
    }
    public function loadInstructors()
    {
        $this->instructors = Course_class::with('instructor','program')
            ->whereHas('course', function ($query) {
                return $query->where('year', session('year'))
                    ->where('semester', session('semester'));
            })
            ->whereHas('program.bolum', function ($query) {
                return $query->where('birim_id', $this->unit_id);
            })
            ->get()
            ->pluck('instructor')
            ->sortBy('adi')
            ->filter()
            ->unique('id')
            ->values();
    }
    #[On('instructorSelected')]
    public function instructorSelected($id)
    {
        $this->selectedInstructorId = $id;
        $this->selectedInstructorName = $this->instructors->firstWhere('id', $id)?->name;
        if($this->selectedInstructorName == null){
            $this->selectedInstructorName = Instructor::where('id', $id)->first()?->name;
        }
    }
    public function render()
    {
        return view('livewire.pages.instructor.index');
    }
}
