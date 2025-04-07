<?php

namespace App\Services;

use App\Models\Course_class;
use App\Models\ScheduleSlot;
use App\Services\Validators\ClassroomConflictValidator;
use App\Services\Validators\InstructorConflictValidator;

class ScheduleService {

    protected array $conflictValidators = [];

    public function __construct(){
        $this->conflictValidators = [
            new InstructorConflictValidator(),
            new ClassroomConflictValidator()
        ];
    }

    public function addToSchedule($scheduleId, $courseId, $day, $startTime, $force = false)
    {
        $course = Course_class::findOrFail($courseId);
        $endTime = date('H:i', strtotime($startTime . ' +45 minute'));

        if ($course->UnscheduledHours < 1) {
            return ['success' => false,'status' => 'Ders zaten planlanmış'];
        }
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

    private function detectConflicts($instructorId, $day, $startTime, string $endTime)
    {
        $conflicts = [];
        foreach ($this->conflictValidators as $validator) {
            $result = $validator->validate($instructorId, $day, $startTime, $endTime);

            if ($result !== true) {
                $conflicts[$validator->getName()] = $result;
            }
        }
        return $conflicts;
    }

}
