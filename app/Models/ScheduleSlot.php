<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScheduleSlot extends Model
{
    protected $table = 'dp_schedule_slots';

    protected $fillable = [];

    public function schedule() {
        return $this->belongsTo('App\Models\Schedule','schedule_id');
    }

    public function course(){
        return  $this->belongsTo(Course_class::class,'course_id','id');
    }

    public function classroom(){
        return  $this->belongsTo(Classroom::class,'classroom_id','id');
    }

    public function getDurationAttribute()
    {
        return $this->start_time && $this->end_time ? $this->end_time->diffInHours($this->start_time) : 0;
    }
}
