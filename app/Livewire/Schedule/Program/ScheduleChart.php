<?php

namespace App\Livewire\Schedule\Program;

use App\Livewire\Schedule\BaseSchedule;
use App\Models\ScheduleSlot;
use App\Services\ScheduleService;
use App\Services\ScheduleSlotProviders\ProgramBasedScheduleSlotProvider;
use Livewire\Attributes\On;

class ScheduleChart extends BaseSchedule
{
    public $program, $year, $semester, $grade = 1;
    public $showInstructorModal = false;
    public $selectedInstructorId = null;
    public $selectedInstructorName = '';

    protected $viewMode = 'program';

    protected function initializeProvider()
    {
        $this->program = session('program') == '' ? -1 : session('program');
        $this->year = session('year') ?? 2000;
        $this->semester = session('semester') ?? 'Fall';

        $this->provider = new ProgramBasedScheduleSlotProvider(
            $this->program,
            $this->year,
            $this->semester,
            $this->grade
        );
    }

    #[On('open-instructor-modal')]
    public function openInstructorModal($instructorId,$instructorName): void
    {
        $this->selectedInstructorId = $instructorId;
        $this->selectedInstructorName = $instructorName;
        $this->showInstructorModal = true;
    }
    #[On('close-modal')]
    public function closeModal(): void
    {
        $this->showInstructorModal = false;
    }


    #[On('filterUpdated')]
    public function applyFilters($filters): void
    {
        $this->program = $filters['program']  == '' ? -1 : $filters['program'];
        $this->year = $filters['year'];
        $this->semester = $filters['semester'];
        $this->loadSchedule();
    }
    #[On('gradeUpdated')]
    public function applyGrade($grade): void
    {
        $this->grade = $grade;
        $this->loadSchedule();
    }

    #[On('addClassroomToSchedule')]
    public function addClassroom($classroomId,$day,$start_time)
    {
        $data = [
            'classroomId' => $classroomId,
            'day' => $day,
            'start_time' =>explode(' - ', $start_time)[0],
        ];
        validator($data, [
            'classroomId' => 'required|integer|exists:dp_classrooms,id',
            'day' => 'required|integer|min:1|max:8',
            'start_time' => 'required|date_format:H:i',
        ])->validate();
        $course = ScheduleSlot::query()->where('schedule_id',$this->schedule->id??null)
            ->where('day',$data['day'])
            ->where('start_time',$data['start_time'])->first();
        if($course){
            $course->classroom_id = $data['classroomId'];

            $course->save();
        }
        $this->loadSchedule();
    }
    #[On('addToSchedule')]
    public function addToSchedule($courseId,$day,$start_time,$scheduleId)
    {
        $startTime = explode(' - ', $start_time)[0];
        $result = app(ScheduleService::class)->addToSchedule(
            $scheduleId,
            $courseId,
            $day,
            $startTime
        );
        if(isset($result['success']) && !$result['success']){
            $this->dispatch('show-confirm', [
                'message' => 'Dersin bütün saatleri zaten planlanmış durumda',
                'type' => 'error'
            ]);
            return;
        }
        if (isset($result['has_conflicts'])) {
            $this->dispatch('ask-confirmation', [
                'message' => $this->formatConflictMessage($result['conflicts']),
                'courseId' => $courseId,
                'day' => $day,
                'start_time' => $start_time
            ]);
            return;
        }

        ;
        $this->dispatch('show-confirm', [
            'message' => 'Ders Çakışma olmadan eklendi.',
            'type' => 'success'
        ]);
        $this->initializeProvider();
        $this->loadSchedule();
    }
    #[On('forceAddToSchedule')]
    public function forceAddToSchedule($courseId, $day, $start_time)
    {
        $startTime = explode(' - ', $start_time)[0];

        app(ScheduleService::class)->addToSchedule(
            $this->schedule->id,
            $courseId,
            $day,
            $startTime,
            true // Force add
        );

        $this->loadSchedule();
        $this->dispatch('show-confirm', [
            'message' => 'Ders Başarı İle eklendi (Çakışma görmezden gelindi.).',
            'type' => 'success'
        ]);
        $this->loadSchedule();
    }
    protected function formatConflictMessage($conflicts)
    {
        $messages = [];
        foreach ($conflicts as $type => $data) {
            $messages[] = $data['message'] ?? '';

            if (isset($data['conflicts'])) {
                $messages = $data['conflicts'];
                #$messages[] = "\n- {$conflict['course']['course']['name']} at {$conflict['start_time']}\n\n";
            }
        }

        return $messages . "\n\nYinede Eklemek İstiyor musun?";
    }
    #[On('removeFromSchedule')]
    public function removeFromSchedule($hour,$day,$courseId): void
    {
        ScheduleSlot::query()
            ->where('schedule_id',$this->schedule->id)
            ->where('day',$day)
            ->where('start_time',explode(' - ', $hour)[0])
            ->where('course_id',$courseId)
            ->delete();
        $this->loadSchedule();

    }
    public function render()
    {
        return view('livewire.schedule.program.schedule-chart');
    }
}
