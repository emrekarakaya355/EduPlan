<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course_class extends Model
{
    protected $table = 'dp_course_classes';
    public $timestamps = true;
    protected $fillable = [
        'branch',
        'external_id',
        'grade',
        'instructorName',
        'instructorSurname',
        'instructorEmail',
        'instructorTitle',
        'external_id'
    ];


    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id', 'id');
    }

    public function program()
    {
        return $this->belongsTo(Program::class, 'program_id', 'id');
    }
}
