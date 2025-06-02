<?php

namespace App\Models;

use App\Enums\DayOfWeek;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $table = 'dp_schedules';

    protected $fillable = ['program_id','year','semester','grade','schedule_config_id', 'show_saturday', 'show_sunday'];
    protected $casts = [
        'show_saturday' => 'boolean',
        'show_sunday' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function program(){
        return $this->belongsTo('App\Models\Program');
    }
    public function scheduleSlots() {
        return $this->hasMany(ScheduleSlot::class,'schedule_id', 'id');
    }
    public function scheduleConfig(){
        return $this->belongsTo(ScheduleConfig::class,'schedule_config_id', 'id');
    }

    public function getResolvedScheduleConfigAttribute()
    {
        if ($this->schedule_config_id && $this->scheduleConfig) {
            return $this->scheduleConfig;
        }
        $shift = str_contains($this->program->name, 'İkinci Öğretim') ? 'night' : 'day';
        return ScheduleConfig::where('shift', $shift)
            ->where('default', true)
            ->first();
    }
    public function getSemesterAttribute($value)
    {
        $translations = [
            'Spring' => 'Bahar',
            'Fall' => 'Güz',
            'Summer' => 'Yaz',
        ];
        return $translations[$value] ?? $value;
    }

    public function getShowDays()
    {
        $days = collect([
            DayOfWeek::Monday,
            DayOfWeek::Tuesday,
            DayOfWeek::Wednesday,
            DayOfWeek::Thursday,
            DayOfWeek::Friday,
        ]);

        if ($this->show_saturday) {
            $days->push(DayOfWeek::Saturday);
        }

        if ($this->show_sunday) {
            $days->push(DayOfWeek::Sunday);
        }

        return $days;
    }
}
