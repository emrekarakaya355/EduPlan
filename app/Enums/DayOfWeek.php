<?php

namespace App\Enums;

enum DayOfWeek: int
{
    case Monday = 1;
    case Tuesday = 2;
    case Wednesday = 3;
    case Thursday = 4;
    case Friday = 5;
    case Saturday = 6;
    case Sunday = 7;


    public function getLabel(): string
    {
        return match($this) {
            self::Monday => 'Pazartesi',
            self::Tuesday => 'Salı',
            self::Wednesday => 'Çarşamba',
            self::Thursday => 'Perşembe',
            self::Friday => 'Cuma',
            self::Saturday => 'Cumartesi',
            self::Sunday => 'Pazar',
        };
    }
}
