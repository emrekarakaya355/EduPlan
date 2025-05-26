<?php

namespace App\Services\Validators;

use App\Contracts\ConflictValidatorInterface;
use App\Enums\ConflictType;
use App\Models\ScheduleSlot;

class CourseDuplicateValidator implements ConflictValidatorInterface
{

    public function validate($validationData)
    {
        if(!$validationData->classId){
            return false;
        }
        $conflicts = ScheduleSlot::where('schedule_id', $validationData->scheduleId)
            ->where('class_id', $validationData->classId)
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
            'message' => 'Aynı Saate Koyulamaz',
            'conflicts' => $this->getConflictMessage($conflicts)
        ];

    }
    private function getConflictMessage($conflicts): string
    {
        return 'Ders Aynı Saate Koyulamaz';
    }
    public function getName()
    {
        return 'course_conflict';
    }

    /**
     * @return mixed
     */
    public function getAction()
    {
        return ConflictType::BLOCKING;
    }
}
