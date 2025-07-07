<?php

namespace Database\Seeders;

use App\Models\Birim;
use App\Models\Bolum;
use App\Models\Building;
use App\Models\Campus;
use App\Models\Classroom;
use App\Models\Course;
use App\Models\Course_class;
use App\Models\Instructor;
use App\Models\Program;
use App\Models\Schedule;
use App\Models\ScheduleConfig;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {/*        ScheduleConfig::create([
            'name' => 'Birinci Öğretim',
            'start_time' => '08:30',
            'end_time' => '18:00',
            'slot_duration' => 45,
            'break_duration' => 15,
            'shift' =>'day',
            'default' =>true
        ]);

        ScheduleConfig::create([
            'name' => 'İkinci Öğretim',
            'start_time' => '18:00',
            'end_time' => '23:59',
            'slot_duration' => 45,
            'break_duration' => 15,
            'shift' =>'night',
            'default' =>true
        ]);
*/
        $this->call(RolePermissionSeeder::class);
    }
}
