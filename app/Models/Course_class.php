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
        'instructorId',
        'external_id'
    ];


    public function course(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id', 'id');
    }

    public function program(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Program::class, 'program_id', 'id');
    }

    public function instructor(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Instructor::class, 'instructorId', 'id');
    }

    public function getDetailColumns()
    {
        return [
            'Ders Adı' => $this->course->name,
            'Ders Kodu' => $this->course->code,
            'Kontenjan' => $this->quota . ' kişi',
            'Süre' => $this->duration . ' saat',
            'Hoca' =>$this->instructorTitle.' '. $this->instructorName.' '.$this->instructorSurname,
            'Dersin Verileceği Sınıf: ' => ''
        ];
    }

}
