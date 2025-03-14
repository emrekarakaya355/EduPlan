<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $table = 'dp_schedules';

    protected $fillable = ['program_id','year','semester','grade','interval'];

    public function program(){
        return $this->belongsTo('App\Models\Program');
    }
    public function scheduleSlots() {
        return $this->hasMany(ScheduleSlot::class,'schedule_id', 'id');
    }

}
