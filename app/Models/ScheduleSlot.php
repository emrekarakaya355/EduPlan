<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class ScheduleSlot extends Model
{
    protected $table = 'dp_schedule_slots';
    protected $casts = [
        'start_time' => 'datetime:H:i',
    ];
    protected $fillable = ['schedule_id','course_id','classroom_id','start_time','end_time','day'];

    public function schedule() {
        return $this->belongsTo('App\Models\Schedule','schedule_id');
    }

    public function course(){
        return  $this->belongsTo(Course_class::class,'course_id','id');
    }

    public function classroom(){
        return  $this->belongsTo(Classroom::class,'classroom_id','id');
    }

    public function getStartTimeAttribute($value)
    {
        return Carbon::createFromFormat('H:i', Carbon::parse($value)->format('H:i'));
    }

    public function getEndTimeAttribute($value)
    {
        return Carbon::createFromFormat('H:i', Carbon::parse($value)->format('H:i'));
    }
    public function getDurationAttribute()
    {
        return $this->start_time && $this->end_time ? $this->start_time->diffInHours($this->end_time) : 0;
    }
}
