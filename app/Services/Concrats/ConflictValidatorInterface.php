<?php
namespace App\Services\Concrats;

interface ConflictValidatorInterface
{
    public function validate($dynamicId, $day, $startTime, $endTime);
    public function getName();
}
