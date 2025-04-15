<?php

namespace App\Traits;

use App\Enums\DayOfWeek;
use Carbon\Carbon;

trait UsesScheduleDataFormatter
{
    public function prepareScheduleSlotData($scheduleSlots): array
    {
        $scheduleData = [];
        if(!$scheduleSlots) {
            return $scheduleData;
        }
        $timeRange = $this->generateTimeRange();
        foreach ($timeRange as $time)
        {
            $timeText = $time['start'] . ' - ' . $time['end'];
            $scheduleData[$timeText] = [];

            foreach (DayOfWeek::cases() as $index => $day)
            {
                $lessons = $scheduleSlots->where('day', $day)->filter(function ($slot) use ($time) {
                    return $slot->start_time->format('H:i') == $time['start'];
                });

                if ($lessons->isNotEmpty())
                {
                    $temp = [];
                    foreach ($lessons as $scheduleSlot) {
                        $temp[] = [
                            'id' => $scheduleSlot->course->id,
                            'class_name' => $scheduleSlot->course->course?->name,
                            'class_code' => $scheduleSlot->course->course?->code,
                            'instructor_name' => $scheduleSlot->course->instructor
                                ?$scheduleSlot->course->instructorTitle . ' '. $scheduleSlot->course->instructor->name
                                : '(Hoca Belirtilmemiş)',
                            'program_name' => $scheduleSlot->course->program?->name ?? ' ',
                            'instructor_id' => $scheduleSlot->course->instructor?->id  ?? ' ',
                            'classrom_name' => $scheduleSlot->classroom?->name ,
                            'building_name' => isset($scheduleSlot->classroom?->building?->name) ? '( ' .$scheduleSlot->classroom?->building?->name .' ) ' :'',
                        ];

                    }
                    $scheduleData[$timeText][] = $temp;
                }else{
                    $scheduleData[$timeText][] = 0;
                }
            }
        }
        return $scheduleData;
    }

    public function generateTimeRange($from = '08:30', $to = '18:00', $lunchStart = '12:00', $lunchEnd = '13:00'){
        {
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
                    'end' => $time->addMinutes(45)->format("H:i")
                ];
                $time->addMinutes(15)->format("H:i");
            } while ($time->format("H:i") < $to);

            return $timeRange;
        }
    }
}
