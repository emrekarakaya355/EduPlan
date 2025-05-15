<?php

namespace App\Services\Validators;

use App\Contracts\ConflictValidatorInterface;
use App\Models\ScheduleSlot;

class ClassroomConflictValidator implements ConflictValidatorInterface
{


    /**
     * @param $dynamicId
     * @param $day
     * @param $startTime
     * @param $endTime
     * @param $classId
     * @param $scheduleId
     * @param $course
     * @return mixed
     */
    public function validate($dynamicId, $day, $startTime, $endTime, $classId = null)
    {
        if($dynamicId == 70){
            return true;
        }
        $conflicts = ScheduleSlot::where('classroom_id', $dynamicId)
            ->where('day', $day)
            ->where('courseClassç.external_id','<>',$classId)
            ->where(function($query) use ($startTime, $endTime) {
                $query->whereBetween('start_time', [$startTime, $endTime])
                    ->orWhereBetween('end_time', [date('H:i', strtotime($startTime . ' +1 minute')), $endTime])
                    ->orWhere(function($q) use ($startTime, $endTime) {
                        $q->where('start_time', '<=', $startTime)
                            ->where('end_time', '>=', $endTime);
                    });
            })
            ->with('courseClass.course')
            ->get();

        dd($conflicts);
        if ($conflicts->isEmpty()) {
            return true;
        }
        return [
            'message' => 'Sınıf Dolu!',
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
            $coursename = $conflict?->courseClass?->course?->name ?? '???';
            $startTime = $conflict?->startTime ?? '???';
            $endTime = $conflict?->endTime ?? '???';
            $message .= "$startTime ile $endTime arasında $coursename dersi bu sınıfta yapılıyor.";
        }
        return $message;

    }
}
