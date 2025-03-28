<?php

namespace App\Services\Validators;

use App\Services\Concrats\ConflictValidatorInterface;

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
    public function validate($scheduleId, $course, $day, $startTime, $endTime)
    {
        return true;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return "classroom conflict";
    }
}
