<?php

namespace App\Services\Validators;


use App\Models\ScheduleSlot;
use App\Services\Concrats\ConflictValidatorInterface;

class InstructorConflictValidator implements ConflictValidatorInterface{


    /**
     * @param $scheduleId
     * @param $course
     * @param $day
     * @param $startTime
     * @param $endTime
     * @return mixed
     */
    public function validate($scheduleId, $course, $day, $startTime, $endTime)
    {
        $conflicts = ScheduleSlot::where('schedule_id', $scheduleId)
            ->whereHas('course', function($query) use ($course) {
                $query->where('instructorId', $course->instructorId);
            })
            ->where('day', $day)
            ->where(function($query) use ($startTime, $endTime) {
                $query->whereBetween('start_time', [$startTime, $endTime])
                    ->orWhereBetween('end_time', [$startTime, $endTime])
                    ->orWhere(function($q) use ($startTime, $endTime) {
                        $q->where('start_time', '<=', $startTime)
                            ->where('end_time', '>=', $endTime);
                    });
            })
            ->with('course')
            ->get();

        if ($conflicts->isEmpty()) {
            return true;
        }

        return [
            'message' => 'Teacher has conflicting classes',
            'conflicts' => $conflicts->toArray()
        ];
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return 'teacher_conflict';
    }
}
