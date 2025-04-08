<?php

namespace App\Services;

use App\Models\Course_class;
use App\Models\ScheduleSlot;
use App\Services\Validators\ClassroomConflictValidator;
use App\Services\Validators\InstructorConflictValidator;

class ScheduleService {

    protected array $courseValidators = [];
    protected array $classroomValidators = [];

    public function __construct(){
        $this->courseValidators = [
            new InstructorConflictValidator(),
        ];
        $this->classroomValidators = [
            new ClassroomConflictValidator(),
        ];
    }

    public function addToSchedule($scheduleId, $courseId, $day, $startTime, $force = false)
    {
        $course = Course_class::find($courseId);
        if(!$course){
            return ['success' => false];
        }

        if ($course->UnscheduledHours < 1) {
            return ['success' => false,'status' => 'Ders zaten planlanmış'];
        }
        $endTime = date('H:i', strtotime($startTime . ' +45 minute'));
        $conflicts = $this->detectConflicts($course->instructorId, $day, $startTime, $endTime);

        if (!empty($conflicts) && !$force) {
            return ['has_conflicts' => true, 'conflicts' => $conflicts];
        }

        $slot = ScheduleSlot::create([
            'schedule_id' => $scheduleId,
            'course_id' => $courseId,
            'classroom_id' => null,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'day' => $day,
        ]);

        return ['success' => true, 'slot' => $slot];
    }

    private function detectConflicts($instructorId, $day, $startTime, $endTime): array
    {
        $conflicts = [];
        foreach ($this->courseValidators as $validator) {
            $result = $validator->validate($instructorId, $day, $startTime, $endTime);

            if ($result !== true) {
                $conflicts[$validator->getName()] = $result;
            }
        }
        return $conflicts;
    }

}
