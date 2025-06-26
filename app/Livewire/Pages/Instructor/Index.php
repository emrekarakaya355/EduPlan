<?php

namespace App\Livewire\Pages\Instructor;
use App\Models\Course_class;
use App\Models\Instructor;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Haftalık Programlar')]
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
        $this->unit_id = Session::get('unit') ?? -1;
        $this->year = Session::get('year') ?? 2000;
        $this->semester = Session::get('semester') ?? 'Güz';
    }

    public function applyFilters($filters)
    {
        $this->unit_id = $filters['unit'];
        $this->year = $filters['year'];
        $this->semester = $filters['semester'];
    }
    #[On('instructorSelected')]
    public function instructorSelected($id,$name)
    {
        $this->selectedBuildingId = null;
        $this->selectedClassroomId = null;
        if (empty($id)) {
            $this->selectedInstructorId = null;
            $this->selectedInstructorName = null;
        } else {
            $this->selectedInstructorId = $id;
             $this->selectedInstructorName = $name;
        }
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
