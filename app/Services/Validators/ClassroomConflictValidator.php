<?php

namespace App\Services\Validators;

use App\Contracts\ConflictValidatorInterface;
use App\Models\ScheduleSlot;

class ClassroomConflictValidator implements ConflictValidatorInterface
{


    /**
     * @param $scheduleId
     * @param $course
     * @param $day
     * @param $startTime
     * @param $endTime
     * @return mixed
     */
    public function validate($dynamicId, $day, $startTime, $endTime)
    {
        $conflicts = ScheduleSlot::where('classroom_id', $dynamicId)
            ->where('day', $day)
            ->where(function($query) use ($startTime, $endTime) {
                $query->whereBetween('start_time', [$startTime, $endTime])
                    ->orWhereBetween('end_time', [date('H:i', strtotime($startTime . ' +1 minute')), $endTime])
                    ->orWhere(function($q) use ($startTime, $endTime) {
                        $q->where('start_time', '<=', $startTime)
                            ->where('end_time', '>=', $endTime);
                    });
            })
            ->with('course.course')
            ->get();
        dd($conflicts);
        if ($conflicts->isEmpty()) {
            return true;
        }
        return [
            'message' => 'Hocanın bu saatte başka bir yerde dersi var.',
            'conflicts' => $this->getConflictMessage($conflicts)
        ];
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return "classroom conflict";
    }

    private function getConflictMessage($conflicts)
    {
        $message = '';
        foreach($conflicts as $conflict){

            $coursename = $conflict?->course?->course?->name ?? '???';

            $message .= "$coursename dersi bu saatte bu sınıfta yapılıyor.";
        }
        return $message;

    }
}
