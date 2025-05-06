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
    public function getColorForClass($key)
    {
         $colors = [
            '#4E79A7', // muted blue
            '#F28E2B', // orange
            '#E15759', // soft red
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
