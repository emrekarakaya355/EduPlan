<?php

namespace App\Dto;

class ScheduleValidationData
{
    public function __construct(
        public int $scheduleId,
        public int $classId,
        public ?int $classroomId,
        public ?int $instructorId,
        public int $day,
        public string $startTime,
        public string $endTime,
        public ?int $externalId,
    ) {}


}
