<?php
namespace App\Services\Concrats;

interface ConflictValidatorInterface
{
    public function validate($scheduleId, $course, $day, $startTime, $endTime);
    public function getName();
}
