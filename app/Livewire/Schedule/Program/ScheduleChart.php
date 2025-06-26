<?php

namespace App\Livewire\Schedule\Program;

use App\Enums\DayOfWeek;
use App\Livewire\Schedule\Shared\BaseSchedule;
use App\Models\Course;
use App\Models\Course_class;
use App\Models\Program;
use App\Models\Schedule;
use App\Models\ScheduleSlot;
use App\Services\Pdf\PdfService;
use App\Services\Pdf\Strategies\ScheduleChartPdf;
use App\Services\ScheduleService;
use App\Services\ScheduleSlotProviders\ProgramBasedScheduleSlotProvider;
use Livewire\Attributes\On;

class ScheduleChart extends BaseSchedule
{
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
        $program = Program::find($this->program);
        $this->grades = $program?->grades ?? [1];
        if (is_array($this->grades) && !empty($this->grades)) {
            if( is_null($this->grade))
                $this->grade = min($this->grades);
        } else {
            $this->grade = 1;
        }
        $this->provider = new ProgramBasedScheduleSlotProvider(
            $this->program,
            $this->year,
            $this->semester,
            $this->grade
        );
    }
    protected $listeners = [
        'open-settings-modal' => 'openSettings',
        'close-settings-modal' => 'closeSettings'
    ];
    public function openSettings(): void
    {
        $this->showSettings = true;
    }
    public function closeSettings(): void
    {
        $this->showSettings = false;
    }
    public function toggleLock()
    {
        if(!$this->schedule){
            return;
        }
        if (!\Auth::check()) {
            $this->dispatch('show-confirm', [
                'message' => 'Bu işlemi yapmak için giriş yapmalısınız.',
                'type' => 'error'
            ]);
            return;
        }

        $currentUser = \Auth::id();

        if ($this->isLocked) {
            if ($this->lockedByUserId === $currentUser) {
                $this->schedule->is_locked = false;
                $this->schedule->locked_by_user_id = null;
                $this->schedule->save();

                $this->isLocked = false;
                $this->lockedByUserId = null;

                $this->dispatch('show-confirm', [
                    'message' => 'Ders programının kilidi açıldı.',
                    'type' => 'success'
                ]);
            } else {
                $this->dispatch('show-confirm', [
                    'message' => 'Ders programını sadece '. $this->schedule?->lockedBy?->name. ' tarafından açılabilir.',
                    'type' => 'error'
                ]);
            }
        } else {
            $this->schedule->is_locked = true;
            $this->schedule->locked_by_user_id = $currentUser;
            $this->schedule->save();

            $this->isLocked = true;
            $this->lockedByUserId = $currentUser;

            $this->dispatch('show-confirm', [
                'message' => 'Ders programı kilitlendi. Sadece siz açabilirsiniz.',
                'type' => 'success'
            ]);
        }
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
        if ($this->isLocked) {
            $this->dispatch('show-confirm', [
                'message' => 'Ders programı kilitli olduğu için ekleme yapılamaz.',
                'type' => 'error'
            ]);
            return;
        }

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
        if ($this->isLocked) {
            $this->dispatch('show-confirm', [
                'message' => 'Ders programı kilitli olduğu için ekleme yapılamaz.',
                'type' => 'error'
            ]);
            return;
        }
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
        if ($this->isLocked) {
            $this->dispatch('show-confirm', [
                'message' => 'Ders programı kilitli olduğu için ekleme yapılamaz.',
                'type' => 'error'
            ]);
            return;
        }
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
        if ($this->isLocked) {
            $this->dispatch('show-confirm', [
                'message' => 'Ders programı kilitli olduğu için silme yapılamaz.',
                'type' => 'error'
            ]);
            return;
        }
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
    #[On('settings-updated')]
    public function handleSettingsUpdated()
    {
        $this->loadSchedule();
    }
    public function render()
    {
          return view('livewire.schedule.program.schedule-chart');
    }
}
