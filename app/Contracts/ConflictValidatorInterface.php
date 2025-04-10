<?php
namespace App\Contracts;

interface ConflictValidatorInterface
{
    public function validate($dynamicId, $day, $startTime, $endTime);
    public function getName();
}
