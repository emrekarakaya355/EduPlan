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
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Birim::create([
            'id' => 1,
            'name' => 'test'
        ]);
        Bolum::create([
            'id' => 1,
            'name' => 'test',
            'birim_id' => 1
        ]);
        Program::create([
            'id' => 1,
            'name' => 'test',
            'year' => 2024,
            'bolum_id' => 1
        ]);
        Course::create([
            'id' => 1,
            'name' => 'test',
            'code' => 'bm 101',
            'external_id' => 1234,
            'year' => 2024,
            'semester' => 'Fall',
        ]);

        Course::create([
            'id' => 2,
            'name' => 'Database Systems',
            'code' => 'cse 102',
            'external_id' => 5678,
            'year' => 2024,
            'semester' => 'Fall',
        ]);
        Course::create([
            'id' => 3,
            'name' => 'Database Systems',
            'code' => 'csee 102',
            'external_id' => 56785,
            'year' => 2024,
            'semester' => 'Fall',
        ]);

        Course_class::create([
            'course_id' => 1,
            'program_id' => 1,
            'external_id' => 1001,
            'branch' => 'A',
            'grade' => 1,
            'instructorName' => 'Ali',
            'instructorSurname' => 'Yılmaz',
            'instructorEmail' => 'ali.yilmaz@example.com',
            'instructorTitle' => 'Dr.',
            'instructorId' => 1,
            'duration' => 4,
            'quota' => 30,
            'isScheduled' => false,
        ]);

        Course_class::create([
            'course_id' => 2,
            'program_id' => 1,
            'external_id' => 1002,
            'branch' => 'B',
            'grade' => 1,
            'instructorName' => 'Ayşe',
            'instructorSurname' => 'Demir',
            'instructorEmail' => 'ayse.demir@example.com',
            'instructorTitle' => 'Prof.',
            'instructorId' => 2,
            'duration' => 4,
            'quota' => 25,
            'isScheduled' => false,
        ]);
        Course_class::create([
            'course_id' => 3,
            'program_id' => 1,
            'external_id' => 1002,
            'branch' => 'B',
            'grade' => 1,
            'instructorName' => 'Ayşe',
            'instructorSurname' => 'Demir',
            'instructorEmail' => 'ayse.demir@example.com',
            'instructorTitle' => 'Prof.',
            'instructorId' => 2,
            'duration' => 4,
            'quota' => 25,
            'isScheduled' => false,
        ]);
        Schedule::create([
            'program_id' => 1,
            'year' => 2024,
            'semester' => 'Fall',
            'grade' => 1,
            'interval' => 1,
        ]);

        Campus::create([
            'id' => 1,
            'name' => 'test',
        ]);
        Building::create([
            'id' => 1,
            'name' => 'test',
            'campus_id' => 1
        ]);
        Classroom::create([
            'name' => 'A101',
            'building_id' => 1,
            'class_capacity' => 40,
            'exam_capacity' => 35,
            'is_active' => true,
            'type' => 'Sınıf',
        ]);

        Classroom::create([
            'name' => 'Lab202',
            'building_id' => 1,
            'class_capacity' => 25,
            'exam_capacity' => 20,
            'is_active' => true,
            'type' => 'Laboratuar',
        ]);
        Classroom::create([
            'name' => 'Lab203',
            'building_id' => 1,
            'class_capacity' => 25,
            'exam_capacity' => 20,
            'is_active' => true,
            'type' => 'Laboratuar',
        ]);
        Classroom::create([
            'name' => 'Lab204',
            'building_id' => 1,
            'class_capacity' => 25,
            'exam_capacity' => 20,
            'is_active' => true,
            'type' => 'Laboratuar',
        ]);


        // User::factory(10)->create();
/*
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);*/

    }
}
