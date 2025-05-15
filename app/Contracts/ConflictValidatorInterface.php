<?php
namespace App\Contracts;

interface ConflictValidatorInterface
{
    public function validate($dynamicId, $day, $startTime, $endTime,$classId);
    public function getName();
}
