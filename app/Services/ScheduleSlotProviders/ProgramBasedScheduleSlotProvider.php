<?php

namespace App\Services\ScheduleSlotProviders;


use App\Contracts\ScheduleSlotProviderInterface;
use App\Models\Schedule;
use Illuminate\Support\Collection;

class ProgramBasedScheduleSlotProvider implements ScheduleSlotProviderInterface
{


    protected ?Schedule $schedule;
    public function __construct(protected int $programId,  protected int $year, protected string $semester, protected int $grade) {
        $this->loadSchedule();
    }

    protected function loadSchedule(): void
    {
        $this->schedule = Schedule::query()
            ->where('program_id', $this->programId)
            ->where('year', $this->year)
            ->where('semester', $this->semester)
            ->where('grade', $this->grade)
            ->first();
    }

    public function getSchedule(): ?Schedule
    {
        return $this->schedule;
    }

    public function getScheduleSlots(): Collection
    {
        return $this->schedule?->scheduleSlots ?? collect();
    }
}
