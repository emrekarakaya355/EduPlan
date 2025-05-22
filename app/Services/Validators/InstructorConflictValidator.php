<?php

namespace App\Services\Validators;


use App\Contracts\ConflictValidatorInterface;
use App\Dto\ScheduleValidationData;
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
    public function validate(ScheduleValidationData $validationData)
    {
        if(!$validationData->instructorId ){
            return true;
        }
        $conflicts = ScheduleSlot::whereHas('courseClass', function($query) use ($validationData) {
                $query->where('instructorId', $validationData->instructorId)
                    ->where('external_id', '<>', $validationData->externalId);
            })
            ->where('day', $validationData->day)
            ->where(function($query) use ($validationData) {
                $query->whereBetween('start_time', [$validationData->startTime, $validationData->endTime])
                    ->orWhereBetween('end_time', [date('H:i', strtotime($validationData->startTime . ' +1 minute')), $validationData->endTime])
                    ->orWhere(function($q) use ($validationData) {
                        $q->where('start_time', '<=', $validationData->startTime)
                            ->where('end_time', '>=', $validationData->endTime);
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
