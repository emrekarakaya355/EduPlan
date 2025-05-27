<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduleConfig extends Model
{
    use HasFactory;
    protected $table = 'dp_schedule_configs';

    protected $fillable = [
        'name',
        'start_time',
        'end_time',
        'slot_duration',
        'break_duration',
    ];
    public function schedules()
    {
        return $this->belongsToMany(Schedule::class, 'dp_schedule_schedule_config', 'schedule_config_id', 'schedule_id');
    }
}
