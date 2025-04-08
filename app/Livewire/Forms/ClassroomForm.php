<?php

namespace App\Livewire\Forms;

use App\Models\Building;
use App\Models\Classroom;
use Livewire\Attributes\Validate;
use Livewire\Form;

class ClassroomForm extends Form
{
    public ?Classroom $classroom;

    public $selectedDepartments;

    #[Validate('required|string|max:255')]
    public $name = '';

    #[Validate('required|exists:dp_buildings,id')]
    public $building_id = '';

    #[Validate('required|integer|min:1')]
    public $class_capacity = 30;

    #[Validate('required|integer|min:1')]
    public $exam_capacity = 30;

    #[Validate('required|in:Laboratuar,Sınıf,Atölye,Salon,Ö.Ü. Odası,Seminer Odası,Anfi')]
    public $type = 'Sınıf';

    public function setClassroom(Classroom $classroom) {
        $this->name = $classroom->name;
        $this->building_id = $classroom->building_id;
        $this->class_capacity = $classroom->class_capacity;
        $this->exam_capacity = $classroom->exam_capacity;
        $this->type = $classroom->type;
        $this->classroom = $classroom;
    }

    public function store() {
        $this->validate();
        $classroom = Classroom::create(
            $this->only(['name', 'building_id', 'class_capacity', 'exam_capacity', 'type'])
        );
        $classroom->birims()->sync($this->building_id);
    }

    public function update() {
        $this->validate();
        $this->article->update(
            $this->only(['name', 'building_id', 'class_capacity', 'exam_capacity', 'type'])
        );
    }
}
