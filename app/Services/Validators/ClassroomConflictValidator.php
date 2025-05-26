<?php

namespace App\Services\Validators;

use App\Contracts\ConflictValidatorInterface;
use App\Enums\ConflictType;
use App\Models\ScheduleSlot;

class ClassroomConflictValidator implements ConflictValidatorInterface
{


    /**
     * @param $dynamicId
     * @param $day
     * @param $startTime
     * @param $endTime
     * @param null $classId
     * @return mixed
     */
    public function validate($validationData)
    {
        if($validationData->classroomId == 70 || $validationData->classroomId == 43){
            return true;
        }
        $conflicts = ScheduleSlot::where('classroom_id', $validationData->classroomId)
            ->where('day', $validationData->day)
            ->whereHas('courseClass', function ($query) use ($validationData) {
                return $query->where('external_id','<>',$validationData->externalId);
            })
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
            'message' => 'Sınıf Dolu!',
            'conflicts' => $this->getConflictMessage($conflicts),
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
            $programName = $conflict?->courseClass?->program?->name ?? '???';
            $startTime = $conflict?->start_time ? $conflict->start_time->format('H:i') : '???';
            $endTime = $conflict?->end_time ? $conflict->end_time->format('H:i') : '???';
            $message .= "$startTime ile $endTime arasında $coursename dersi bu sınıfta yapılıyor. \n $programName";
        }
        return $message;
    }

    /**
     * @return mixed
     */
    public function getAction()
    {
        return ConflictType::BLOCKING;
    }
}
