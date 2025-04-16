<?php

namespace App\Services\ScheduleSlotProviders;

use App\Contracts\ScheduleSlotProviderInterface;
use App\Models\Schedule;
use App\Models\ScheduleSlot;
use Illuminate\Support\Collection;

class InstructorBasedScheduleSlotProvider implements ScheduleSlotProviderInterface
{

    public function  __construct(protected int $instructorId){}

    /**
     * @return Collection
     */
    public function getScheduleSlots(): Collection
    {

        return ScheduleSlot::with(['courseClass', 'courseClass.instructor'])
            ->whereHas('courseClass', function ($q) {
                $q->where('instructorId', $this->instructorId);
            })
            ->get();

    }

    /**
     * @return Schedule|null
     */
    public function getSchedule(): ?Schedule
    {
    }
}
