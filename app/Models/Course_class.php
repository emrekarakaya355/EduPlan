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

    public function scheduleSlots()
    {
        return $this->hasMany(ScheduleSlot::class, 'class_id', 'id');
    }

    public function getDetailColumns()
    {
        return [
            'Ders Adı' => $this->course->name,
            'Ders Kodu' => $this->course->code,
            'Kontenjan' => $this->quota . ' kişi',
            'Süre' => $this->duration . ' saat',
            'Hoca' =>   $this->instructorTitle.' '. $this->instructorName.' '.$this->instructorSurname,
            'Dersin Verileceği Sınıf: ' => ''
        ];
    }


    public function setTemporaryScheduledHours($hours)
    {
        $this->attributes['temporary_scheduled_hours'] = $hours;
    }
    public function getScheduledHoursAttribute()
    {
        return $this->scheduleSlots->count();
    }

    public function getUnscheduledHoursAttribute()
    {
        $scheduledHours = $this->scheduled_hours ?? 0;

        return max(0, $this->duration - $scheduledHours);
    }

    public function scopeScheduled($query)
    {
        return $query->whereHas('scheduleSlots', function ($query) {
            $query->where('duration', '>', 0);
        });
    }

    public function scopeUnscheduled($query)
    {
        return $query->whereDoesntHave('scheduleSlots', function ($query) {
            $query->where('duration', '>', 0);
        });
    }

}
