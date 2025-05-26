<?php

namespace App\Services\Validators;

use App\Contracts\ConflictValidatorInterface;
use App\Dto\ScheduleValidationData;
use App\Enums\ConflictType;

class ClassroomTypeConflictValidator implements ConflictValidatorInterface
{

    public function validate(ScheduleValidationData $validationData)
    {
        // TODO: Implement validate() method.
    }

    public function getName()
    {
        return "classroom Type conflict";
    }

    public function getAction()
    {
        return ConflictType::WARNING;
    }
}
