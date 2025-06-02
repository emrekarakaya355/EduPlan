<?php

namespace App\Traits;

use App\Enums\DayOfWeek;
use App\Models\Schedule;
use App\Models\ScheduleConfig;
use Carbon\Carbon;

trait UsesScheduleDataFormatter
{
    public function prepareScheduleSlotData($scheduleSlotProvider): array
    {
        $scheduleSlots = $scheduleSlotProvider->getScheduleSlots();
        $schedule = $scheduleSlotProvider->getSchedule();

        $scheduleData = [];
        if(!$scheduleSlots) {
            return $scheduleData;
        }
        $config = $schedule?->resolvedScheduleConfig;
        $timeRange = $this->generateTimeRange(
            $config?->start_time,
            $config?->end_time,
            $config?->slot_duration,
            $config?->break_duration
        );
        //$usedDays = $scheduleSlots->pluck('day')->unique()->map(fn($i) => DayOfWeek::from($i));

        /*
        if ($usedDays->contains(DayOfWeek::Sunday)) {
            $daysToShow = collect(DayOfWeek::cases());
        }
        else{
            $daysToShow = collect(DayOfWeek::cases())->filter(fn($day) =>
                $usedDays->contains($day)
                || in_array($day,
                    [
                        DayOfWeek::Monday,
                        DayOfWeek::Tuesday,
                        DayOfWeek::Wednesday,
                        DayOfWeek::Thursday,
                        DayOfWeek::Friday,
                    ])
            );
        }*/

        $daysToShow = $schedule?->getShowDays() ?? collect(
            [DayOfWeek::Monday,
            DayOfWeek::Tuesday,
            DayOfWeek::Wednesday,
            DayOfWeek::Thursday,
            DayOfWeek::Friday]
        );
        foreach ($timeRange as $time)
        {
            $timeText = $time['start'] . ' - ' . $time['end'];
            $scheduleData[$timeText] = [];
            foreach ($daysToShow as $index => $day)
            {
                $lessons = $scheduleSlots->where('day', $day)->filter(function ($slot) use ($time) {
                    return $slot->start_time->format('H:i')>=$time['start'] &&$slot->start_time->format('H:i') <$time['end'];
                });

                if ($lessons->isNotEmpty())
                {
                    $temp = [];
                    foreach ($lessons as $scheduleSlot) {

                        $temp[] = [
                            'id' => $scheduleSlot->courseClass->id,
                            'class_name' => $scheduleSlot->courseClass->course?->name,
                            'class_code' => $scheduleSlot->courseClass->course?->code,
                            'instructor_name' => $scheduleSlot->courseClass->instructor
                                ?$scheduleSlot->courseClass->instructorTitle . ' '. $scheduleSlot->courseClass->instructor->name
                                : '(Hoca Belirtilmemiş)',
                            'program_name' => $scheduleSlot->courseClass->program?->name ?? '',
                            'instructor_id' => $scheduleSlot->courseClass->instructor?->id  ?? '',
                            'classrom_name' => $scheduleSlot->classroom?->name ,
                            'building_name' => isset($scheduleSlot->classroom?->building?->name) ? '( ' .$scheduleSlot->classroom?->building?->name .' ) ' :'',
                            'color' => $this->getColorForClass(($scheduleSlot->courseClass?->id.$scheduleSlot->courseClass?->name) ),
                            'external_id' => $scheduleSlot->courseClass->external_id ?? '',
                            'commonLesson' => $scheduleSlot->courseClass->commonLessons->isNotEmpty(),
                        ];

                    }
                    $scheduleData[$timeText][] = $temp;
                }else{
                    $scheduleData[$timeText][] = 0;
                }
            }
        }

        return [
            'scheduleData' => $scheduleData,
            'days' => $daysToShow->map(fn($d) => [
                'label' => $d->getLabel(),
                'value' => $d->value
            ])->values()
        ];
    }

    public function generateTimeRange($from, $to,$slotInterval,$breakDuration, $lunchStart = '12:30', $lunchEnd = '13:30'){
        {
            $from ??= '08:30';
            $to ??= '18:00';
            $slotInterval ??= 45;
            $breakDuration ??= 15;
            $lunchStart ??= '12:30';
            $lunchEnd ??= '13:30';

            $endOfDay = Carbon::createFromTime(23, 59);
            $time = Carbon::parse($from);
            $lunchStart = Carbon::parse($lunchStart);
            $lunchEnd = Carbon::parse($lunchEnd);
            $timeRange = [];

             do
            {
                if($time>=$lunchStart && $time<=$lunchEnd){
                    $timeRange[] = [
                        'start' => $lunchStart->format("H:i"),
                        'end' => $lunchEnd->format("H:i")
                    ];
                    $time = $lunchEnd->copy();
                }
                $timeRange[] = [
                    'start' => $time->format("H:i"),
                    'end' => $time->addMinutes($slotInterval)->format("H:i")
                ];
                $time->addMinutes($breakDuration)->format("H:i");
            } while ($time->format("H:i") < $to && $time <= $endOfDay);

            return $timeRange;
        }
    }

    public function formatWeeklyAvailability($scheduleSlots)
    {
        $availability = [];
        $timeRange = $this->generateTimeRange();

        foreach ($timeRange as $time) {
            $timeText = $time['start'] . ' - ' . $time['end'];
            $availability[$timeText] = [];

            foreach (DayOfWeek::cases() as $day) {
                $slot = $scheduleSlots
                    ->where('day', $day)
                    ->filter(function ($slot) use ($time) {
                        return $slot->start_time->format('H:i') == $time['start'];
                    })
                    ->first();

                $availability[$timeText][$day->value] = isset($slot) ? $slot->courseClass->course->name: 'boş';
            }
        }

        return $availability;
    }
    public function formatDailyAvailability($scheduleSlots, $selectedDays = [], $startTime = "",$endTime="", $statusFilter = "")
    {
        $availability = [];
        $timeRange = $this->generateTimeRange();

        foreach (DayOfWeek::cases() as $day) {
            if (!empty($selectedDays) && !in_array($day->name, $selectedDays)) {
                continue;
            }
            $availability[$day->getLabel()] = [];

            foreach ($timeRange as $time) {
                if ($startTime && $time['start'] <= $startTime) {
                    continue;
                }

                if ($endTime && $time['end'] >= $endTime) {
                    continue;
                }
                $timeText = $time['start'] . ' - ' . $time['end'];

                $slot = $scheduleSlots
                    ->where('day', $day)
                    ->filter(function ($slot) use ($time) {
                        return $slot->start_time->format('H:i') == $time['start'];
                    })
                    ->first();
                $status = isset($slot) ? $slot->courseClass->course->name : 'boş';
                if($statusFilter == ""){
                    $availability[$day->getLabel()][$timeText] = $status;
                }
                if (!filter_var($statusFilter, FILTER_VALIDATE_BOOLEAN) && $status !== 'boş') {
                    continue;
                }
                if (filter_var($statusFilter, FILTER_VALIDATE_BOOLEAN) && $status === 'boş') {
                    continue;
                }
                $availability[$day->getLabel()][$timeText] = $status;
            }
        }
        return $availability;
    }

    public function getColorForClass($key)
    {
         $colors = [
            '#F28E2B', // orange
            '#76B7B2', // teal
            '#59A14F', // green
            '#EDC949', // gold
            '#AF7AA1', // muted purple
            '#FF9DA7', // pink
            '#9C755F', // brownish
            '#BAB0AC', // gray
            '#D37295', // rose
            '#FABFD2', // light pink
            '#B07AA1', // dusty purple
            '#86BCB6', // desaturated teal
            '#F1CE63', // mustard
            '#8CD17D', // lime green
            '#499894', // muted cyan
            '#D4A6C8', // lavender
            '#60B6E3', // sky blue
            '#FFBE7D', // peach
        ];

        $hash = 0;
        for ($i = 0; $i < strlen($key); $i++) {
            $hash = ord($key[$i]) + (($hash << 5) - $hash);
        }

        $index = abs($hash) % count($colors);
        return $colors[$index];
    }
}
