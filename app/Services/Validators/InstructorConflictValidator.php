<?php

namespace App\Services\Validators;


use App\Contracts\ConflictValidatorInterface;
use App\Models\ScheduleSlot;

class InstructorConflictValidator implements ConflictValidatorInterface{


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
    public function validate($dynamicId, $day, $startTime, $endTime, $classId)
    {
        if(!$dynamicId ){
            return true;
        }
        $conflicts = ScheduleSlot::whereHas('courseClass', function($query) use ($dynamicId,$classId) {
                $query->where('instructorId', $dynamicId)
                    ->where('external_id', '<>', $classId);;
            })
            ->where('day', $day)
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

        if ($conflicts->isEmpty()) {
            return true;
        }
        return [
            'message' => 'Hocanın bu saatte başka bir yerde dersi var.',
            'conflicts' => $this->getConflictMessage($conflicts)
        ];
    }

    private function getConflictMessage($conflicts): string
    {
        $message = '';
        foreach($conflicts as $conflict){

            $instructorName = $conflict?->courseClass?->instructor?->name ?? ' ?? ';
            $programName = $conflict?->courseClass?->program?->name ?? ' bir yerde ';
            $courseCode = $conflict?->courseClass?->course?->code ?? '???';

            $message .= "$instructorName isimli hocamız bu saatte $programName programı için $courseCode kodlu dersi vermektedir.\n";
        }
        return $message;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return 'teacher_conflict';
    }
}
