<?php
namespace App\Contracts;

use App\Dto\ScheduleValidationData;

interface ConflictValidatorInterface
{
    public function validate(ScheduleValidationData $validationData);
    public function getName();
}
