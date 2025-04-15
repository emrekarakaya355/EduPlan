<?php

namespace App\Services\ScheduleSlotProviders;


use App\Contracts\ScheduleSlotProviderInterface;
use App\Models\Schedule;
use App\Models\ScheduleSlot;
use Illuminate\Support\Collection;

class CourseBasedScheduleSlotProvider implements ScheduleSlotProviderInterface{


    public function __construct(protected int  $courseId){}

    /**
     * @return Schedule|null
     */
    public function getSchedule(): ?Schedule {return null;}

    /**
     * @return Collection
     */
    public function getScheduleSlots(): Collection
    {
        return ScheduleSlot::with(['course.course', 'course.instructor'])
            ->whereHas('course.course', function ($q) {
                $q->where('course_id', $this->courseId);
            })
            ->get();
    }
}
