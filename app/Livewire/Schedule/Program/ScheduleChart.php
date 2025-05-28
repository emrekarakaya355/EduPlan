<?php

namespace App\Livewire\Schedule\Program;

use App\Enums\DayOfWeek;
use App\Livewire\Schedule\Shared\BaseSchedule;
use App\Models\Course;
use App\Models\Course_class;
use App\Models\Schedule;
use App\Models\ScheduleSlot;
use App\Services\Pdf\PdfService;
use App\Services\Pdf\Strategies\ScheduleChartPdf;
use App\Services\ScheduleService;
use App\Services\ScheduleSlotProviders\ProgramBasedScheduleSlotProvider;
use Livewire\Attributes\On;

class ScheduleChart extends BaseSchedule
{
    public $program, $year, $semester, $grade = 1;
    public $showInstructorModal = false;
    public $selectedInstructorId = null;
    public $selectedInstructorName = '';

    public $showSettings = false;

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

    public function openSettings(): void
    {
        $this->showSettings = true;
    }
    public function closeSettings(): void
    {
        $this->showSettings = false;
    }
    #[On('open-instructor-modal')]
    public function openInstructorModal($instructorId,$instructorName): void
    {
        if(empty($instructorId) ){
            return;
        }
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
    public function addClassroomToSchedule($classroomId,$day,$start_time,$classId,$externalId)
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

        $result = app(ScheduleService::class)->addClassroomToSlot(
            $classroomId,
            $this->schedule->id,
            $day,
            $data['start_time'],
            $classId,
            $externalId
        );
        if ($result['status'] === 'blocked') {
            $this->dispatch('show-confirm', [
                'message' => $result['conflict']['details'],
                'type' => 'error'
            ]);
            return;
        }
        if ($result['status'] === 'soft_conflicts') {
            foreach ($result['conflicts'] as $conflict) {
                $this->dispatch('ask-confirmation', [
                    'message' => $this->formatConflictMessage($conflict['details']),
                    'classId' => $classId,
                    'day' => $day,
                    'start_time' => $start_time
                ]);
            }
            return;
        }
        $this->dispatch('show-confirm', [
            'message' => 'Derslik Çakışma olmadan eklendi.',
            'type' => 'success'
        ]);
        $this->loadSchedule();
    }
    #[On('addToSchedule')]
    public function addCourseToSchedule($classId,$day,$start_time,$scheduleId)
    {
        $startTime = explode(' - ', $start_time)[0];
        $result = app(ScheduleService::class)->addCourseToSchedule(
            $scheduleId,
            $classId,
            $day,
            $startTime
        );
        if ($result['status'] === 'blocked') {
            $this->dispatch('show-confirm', [
                'message' => $result['conflict']['details'],
                'type' => 'error'
            ]);
            return;
        }


        if ($result['status'] === 'soft_conflicts') {
            foreach ($result['conflicts'] as $conflict) {
                $this->dispatch('ask-confirmation', [
                    'message' => $this->formatConflictMessage($conflict['details']),
                    'classId' => $classId,
                    'day' => $day,
                    'start_time' => $start_time
                ]);
            }
            return;
        }


        $this->dispatch('show-confirm', [
            'message' => 'Ders Çakışma olmadan eklendi.',
            'type' => 'success'
        ]);
        $this->initializeProvider();
        $this->loadSchedule();
    }
    #[On('forceAddToSchedule')]
    public function forceAddCourseToSchedule($classId, $day, $start_time)
    {
        $startTime = explode(' - ', $start_time)[0];
        app(ScheduleService::class)->addCourseToSchedule(
            $this->schedule->id,
            $classId,
            $day,
            $startTime,
            true
        );

        $this->loadSchedule();
        $this->dispatch('show-confirm', [
            'message' => 'Ders Başarı İle eklendi (Çakışma görmezden gelindi.).',
            'type' => 'success'
        ]);
        $this->loadSchedule();
    }
    protected function formatConflictMessage($message)
    {
        return $message . "\n\nYinede Eklemek İstiyor musun?";
    }
    #[On('removeFromSchedule')]
    public function removeFromSchedule($hour,$day,$classId): void
    {
        $courseClass = Course_class::find($classId);
        if($courseClass->external_id){
            ScheduleSlot::query()
                ->where('day',$day)
                ->where('start_time',explode(' - ', $hour)[0])
                ->whereHas('courseClass',function($query) use ($courseClass){
                    $query->where('external_id', $courseClass->external_id);
                })
                ->delete();
        }else{
            ScheduleSlot::query()
                ->where('schedule_id',$this->schedule->id)
                ->where('day',$day)
                ->where('start_time',explode(' - ', $hour)[0])
                ->where('class_id',$classId);
        }
        $this->loadSchedule();
    }

    public function downloadPdf(PdfService $pdfService)
    {
        $strategy = new ScheduleChartPdf();
         return $pdfService->stream($strategy, ['scheduleData' => $this->scheduleData,'schedule'=>$this->schedule]);
    }

    public function render()
    {

         return view('livewire.schedule.program.schedule-chart');
    }
}
