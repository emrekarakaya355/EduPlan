<?php

namespace App\Models;

use App\Enums\DayOfWeek;
use App\Traits\UsesScheduleDataFormatter;
use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    use UsesScheduleDataFormatter;
    protected $table = 'dp_classrooms';
    public $timestamps = true;
    protected $fillable = [
        'name',
        'class_capacity',
        'exam_capacity',
        'type',
        'building_id',
        'is_active',
    ];

    public function building(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Building::class);
    }

    public function birims(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Birim::class, 'dp_birim_classrooms', 'classroom_id', 'birim_id');
    }

    public function bolums(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Bolum::class, 'dp_birim_classrooms', 'classroom_id', 'bolum_id');
    }
    public function scheduleSlots(){
        return  $this->hasMany(ScheduleSlot::class,'classroom_id','id');
    }

    public function getTotalUsageDurationAttribute()
    {
        return $this->scheduleSlots->count();
    }
    public function getWeeklyAvailabilityAttribute()
    {
        $slots = $this->scheduleSlots;
        return $this->formatWeeklyAvailability($slots);
    }

    public function getDailyAvailabilityAttribute($dayRange = [], $timeRangeFilter = [], $statusFilter = null)
    {
        $slots = $this->scheduleSlots;
        return $this->formatDailyAvailability($slots,
            $dayRange,$timeRangeFilter,$statusFilter);
    }
    public function dailyAvailability($dayRange = [], $startTime ="",$endTime="", $statusFilter = null)
    {
        $slots = $this->scheduleSlots;
        return $this->formatDailyAvailability($slots, $dayRange, $startTime,$endTime, $statusFilter);
    }
    public function getDetailColumns()
    {
        return [
            'Derslik Adı' => $this->name,
            'Fakülte' => $this->building->campus->name,
            'Bina' => $this->building->name,
            'Sınıf Türü' => $this->type,
            'Ders Kapasitesi' => $this->class_capacity . ' kişi',
            'Sınav Kapasitesi' => $this->exam_capacity . ' kişi',
        ];
    }
}
