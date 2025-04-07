<?php

namespace App\Livewire\Schedule;

use App\Enums\DayOfWeek;
use App\Models\Schedule;
use App\Models\ScheduleSlot;
use App\Services\ScheduleService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\On;
use Livewire\Component;

class Chart extends Component
{
    public $program, $year, $semester;
    public $grade = 1;
    public $schedule;
    public $scheduleSlots;
    public $scheduleData;
    public function mount(): void
    {
        $this->program = Session::get('program');
        $this->year = Session::get('year');
        $this->semester = Session::get('semester');
        $this->loadSchedule();
    }

    public function loadSchedule(): void
    {
        if (empty($this->program)) {
            $this->schedule = null;
            $this->prepareScheduleSlotData(collect());
        } else {
            $query = Schedule::query();
            $query->where('program_id', $this->program);
            $query->where('year', $this->year);
            $query->where('semester', $this->semester);
            $query->where('grade', $this->grade);
            $query->whereHas('program.courseClasses', function ($q) {
                $q->where('grade', $this->grade);
            });
            $this->schedule = $query->with(['scheduleSlots', 'program.courseClasses.instructor', 'program.courseClasses.course', 'program.courseClasses' => function ($q) {
                $q->where('grade', $this->grade);
            }])->first();

            $this->prepareScheduleSlotData($this->schedule->scheduleSlots ?? collect());
        }
    }

    private function prepareScheduleSlotData($scheduleSlots ): void
    {
        if(!$scheduleSlots) {
            return;
        }
        $timeRange = $this->generateTimeRange();
        foreach ($timeRange as $time)
        {
            $timeText = $time['start'] . ' - ' . $time['end'];
            $this->scheduleData[$timeText] = [];

            foreach (DayOfWeek::cases() as $index => $day)
            {
                $lessons = $scheduleSlots->where('day', $day)->filter(function ($slot) use ($time) {
                    return $slot->start_time->format('H:i') == $time['start'];
                });
                if ($lessons->isNotEmpty())
                {
                    $temp = [];
                    foreach ($lessons as $scheduleSlot) {
                        $temp[] = [
                            'id' => $scheduleSlot->course->id,
                            'class_name' => $scheduleSlot->course->course?->name,
                            'class_code' => $scheduleSlot->course->course?->code,
                            'teacher_name' => ($scheduleSlot->course->instructor?->adi . ' ' . $scheduleSlot->course->instructor?->soyadi) ?? ' ',
                            'classrom_name' => $scheduleSlot->classroom?->name ,
                            'building_name' => isset($scheduleSlot->classroom?->building?->name) ? '( ' .$scheduleSlot->classroom?->building?->name .' ) ' :'',
                        ];
                    }
                    $this->scheduleData[$timeText][] = $temp;
                }else{
                    $this->scheduleData[$timeText][] = 0;
                }
            }
        }

        //dd($this->scheduleData);
    }
    private function generateTimeRange($from = '08:30', $to = '18:00', $lunchStart = '12:00', $lunchEnd = '13:00'){
        {
            $time = Carbon::parse($from);
            $lunchStart = Carbon::parse($lunchStart);
            $lunchEnd = Carbon::parse($lunchEnd);
            $timeRange = [];
            do
            {
                if($time>=$lunchStart && $time<=$lunchEnd){
                    $timeRange[] = [
                        'start' => $lunchStart->format("H:i"),
                        'end' => $lunchEnd->format("H:i")
                    ];
                    $time = $lunchEnd->copy();
                }
                $timeRange[] = [
                    'start' => $time->format("H:i"),
                    'end' => $time->addMinutes(45)->format("H:i")
                ];
                $time->addMinutes(15)->format("H:i");
            } while ($time->format("H:i") < $to);

            return $timeRange;
        }
    }


    #[On('filterUpdated')]
    public function applyFilters($filters): void
    {
        $this->program = $filters['program'];
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
        return view('livewire.schedule.chart');
    }
}
