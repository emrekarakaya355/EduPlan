<?php

namespace App\Services;
use App\Contracts\AcademicServiceInterface;

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

}
