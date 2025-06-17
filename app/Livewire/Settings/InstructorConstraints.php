<?php

namespace App\Livewire\Settings;

use App\Models\Instructor;
use App\Models\InstructorConstraint;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class InstructorConstraints extends Component
{
    public $instructorId;
    public Instructor $instructor;
    public $isModal = false;
    public $constraints = [];
    public $selectedDay = null;
    public $startTime = '';
    public $endTime = '';
    public $note = '';
    public $editingConstraintId = null;
    public $showForm = false;

    public $days = [
        1 => 'Pazartesi',
        2 => 'Salı',
        3 => 'Çarşamba',
        4 => 'Perşembe',
        5 => 'Cuma',
        6 => 'Cumartesi',
        0 => 'Pazar'
    ];

    public $timeSlots = [];

    protected $rules = [
        'selectedDay' => 'required|integer|between:0,6',
        'startTime' => 'required',
        'endTime' => 'required|after:startTime',
        'note' => 'nullable|string|max:500'
    ];

    protected $messages = [
        'selectedDay.required' => 'Lütfen bir gün seçiniz.',
        'startTime.required' => 'Başlangıç saati zorunludur.',
        'endTime.required' => 'Bitiş saati zorunludur.',
        'endTime.after' => 'Bitiş saati başlangıç saatinden sonra olmalıdır.'
    ];

    public function mount($instructorId,$isModal )
    {
        $this->instructorId = $instructorId;
        $this->instructor = Instructor::find($instructorId);
        $this->isModal = $isModal;
        $this->generateTimeSlots();
        $this->loadConstraints();
    }

    public function generateTimeSlots()
    {
        $slots = [];
        for ($hour = 8; $hour <= 22; $hour++) {
            for ($minute = 0; $minute < 60; $minute += 30) {
                $time = sprintf('%02d:%02d', $hour, $minute);
                $slots[] = $time;
            }
        }
        $this->timeSlots = $slots;
    }

    public function loadConstraints()
    {
        $this->constraints = $this->instructor?->constraints()
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get();

    }

    public function openForm($day = null)
    {
        $this->selectedDay = $day;
        $this->startTime = '';
        $this->endTime = '';
        $this->note = '';
        $this->editingConstraintId = null;
        $this->showForm = true;
        $this->resetValidation();
    }

    public function editConstraint($constraintId)
    {
        $constraint = collect($this->constraints)->firstWhere('id', $constraintId);

        if ($constraint) {
            $this->selectedDay = $constraint->day_of_week;
            $this->startTime = substr($constraint->start_time, 0, 5);
            $this->endTime = substr($constraint->end_time, 0, 5);
            $this->note = $constraint->note ?? '';
            $this->editingConstraintId = $constraintId;
            $this->showForm = true;
            $this->resetValidation();
        }
    }

    public function saveConstraint()
    {
        $this->validate();

        if ($this->checkTimeConflict()) {
            $this->addError('timeConflict', 'Bu zaman aralığında başka bir kısıtlama bulunmaktadır.');
            return;
        }

        $data = [
            'instructor_id' => $this->instructorId,
            'day_of_week' => $this->selectedDay,
            'start_time' => $this->startTime,
            'end_time' => $this->endTime,
            'note' => $this->note ?: null,
            'updated_at' => now()
        ];
        if ($this->editingConstraintId) {

            InstructorConstraint::query()
                ->where('id', $this->editingConstraintId)
                ->update($data);

            session()->flash('message', 'Kısıtlama başarıyla güncellendi.');
        } else {
            $data['created_at'] = now();


            InstructorConstraint::query()->create($data);

            session()->flash('message', 'Kısıtlama başarıyla eklendi.');
        }

        $this->closeForm();
        $this->loadConstraints();
    }

    public function deleteConstraint($constraintId)
    {
        InstructorConstraint::query()
            ->where('id', $constraintId)
            ->delete();

        $this->loadConstraints();
        session()->flash('message', 'Kısıtlama başarıyla silindi.');
    }

    public function closeForm()
    {
        $this->showForm = false;
        $this->selectedDay = null;
        $this->startTime = '';
        $this->endTime = '';
        $this->note = '';
        $this->editingConstraintId = null;
        $this->resetValidation();
    }

    private function checkTimeConflict()
    {
        $query = InstructorConstraint::query()
            ->where('instructor_id', $this->instructorId)
            ->where('day_of_week', $this->selectedDay)
            ->where(function ($q) {
                $q->where(function ($subQ) {
                    $subQ->where('start_time', '<=', $this->startTime)
                        ->where('end_time', '>', $this->startTime);
                })->orWhere(function ($subQ) {
                    $subQ->where('start_time', '<', $this->endTime)
                        ->where('end_time', '>=', $this->endTime);
                })->orWhere(function ($subQ) {
                    $subQ->where('start_time', '>=', $this->startTime)
                        ->where('end_time', '<=', $this->endTime);
                });
            });

        if ($this->editingConstraintId) {
            $query->where('id', '!=', $this->editingConstraintId);
        }

        return $query->exists();
    }

    public function getConstraintsByDay($day)
    {
        return collect($this->constraints)->where('day_of_week', $day)->sortBy('start_time');
    }

    public function render()
    {
        return view('livewire.settings.instructor-constraints');
    }
}
