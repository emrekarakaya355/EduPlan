<?php

namespace App\Livewire\Pages\Instructor;
use App\Models\Course_class;
use App\Models\Instructor;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\On;
use Livewire\Component;

class Index extends Component
{
    public $unit_id, $year, $semester;
    public $selectedInstructorId ;
    public $selectedInstructorName;

    public $selectedBuildingId;
    public $selectedClassroomId ;

    protected $listeners = ['filterUpdated' => 'applyFilters'];

    public function mount()
    {
        $this->unit_id = Session::get('unit');
        $this->year = Session::get('year');
        $this->semester = Session::get('semester');
    }

    public function applyFilters($filters)
    {
        $this->unit_id = $filters['unit'];
        $this->year = $filters['year'];
        $this->semester = $filters['semester'];
    }
    #[On('instructorSelected')]
    public function instructorSelected($id )
    {
        $this->selectedBuildingId = null;
        $this->selectedClassroomId = null;
        $this->selectedInstructorId = $id;
        $this->selectedInstructorName = Instructor::where('id', $id)->first()?->name;
    }
    #[On('buildingSelected')]
    public function buildingSelected($id){
        $this->selectedClassroomId = null;
        $this->selectedInstructorId = null;
        $this->selectedInstructorName = null;
        $this->selectedBuildingId = $id;

    }
    #[On('classroomSelected')]
    public function classroomSelected($id)
    {

        $this->selectedClassroomId = $id;

    }
    public function render()
    {
        return view('livewire.pages.instructor.index');
    }
}
