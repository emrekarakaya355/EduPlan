<?php

namespace App\Services\ScheduleSlotProviders;


use App\Contracts\ScheduleSlotProviderInterface;
use App\Models\Schedule;
use App\Models\ScheduleSlot;
use Illuminate\Support\Collection;

class ClassroomBasedScheduleSlotProvider implements ScheduleSlotProviderInterface{


    public function __construct(protected int  $classroomId){}

    /**
     * @return Schedule|null
     */
    public function getSchedule(): ?Schedule {return null;}

    /**
     * @return Collection
     */
    public function getScheduleSlots(): Collection
    {
        return ScheduleSlot::with(['courseClass', 'courseClass.instructor'])
            ->where('classroom_id', $this->classroomId)
            ->get();
    }
}
