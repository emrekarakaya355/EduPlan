<?php

namespace App\Services;
use App\Models\Birim;
use App\Models\Bolum;
use App\Models\Program;
use App\Services\Concrats\AcademicServiceInterface;

class ApiService
{
    private AcademicServiceInterface $ubysService;
    public function __construct(AcademicServiceInterface $ubysService)
    {
        $this->ubysService = $ubysService;

    }

    public function syncData($year,$semester){

        $startTime = microtime(true);

        $this->ubysService->syncUnits($year,$semester);
        $endTime = microtime(true);
        $duration = $endTime - $startTime;

        $this->ubysService->syncLessons($year,$semester);
        dd("bitti");
    }


/*
    private function getBirims($units)
    {
        return $units->filter(function ($unit) {return ($unit->UnitTypeId == "8" || $unit->UnitTypeId == "3" ||$unit->UnitTypeId == "1" || $unit->UnitTypeId == "16"|| $unit->UnitTypeId == "11"); });
    }
*/






}
