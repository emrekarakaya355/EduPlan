<?php

namespace App\Contracts;

use App\Models\Schedule;
use Illuminate\Support\Collection;

interface ScheduleSlotProviderInterface
{
    public function getSchedule(): ?Schedule;
    public function getScheduleSlots(): Collection;

}
