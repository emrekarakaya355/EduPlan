<?php

namespace App\Contracts;

// App\Providers\AppServiceProvider aracılığı ile UbysServiceAdapter sınıfına bağlandı. Yeni  bir servis yazılması durumunda Başka Adapter bağlanmalı.

interface AcademicServiceInterface
{
    public function fetchLessons($year,$donem);
    public function fetchStudents();
    public function fetchUnits($criteria);
    public function fetchPersonPositions ($criteria);
    public function fetchGetWorkers($criteria);
    public function fetchCourseStudentList($criteria);

    public function syncUnits($year,$semester);
    public function syncLessons($year,$semester);

}
