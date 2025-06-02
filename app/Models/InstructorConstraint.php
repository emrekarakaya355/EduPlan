<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstructorConstraint extends Model
{
    protected $table = 'dp_instructor_constraints';

    protected $fillable = [
        'instructor_id',
        'day_of_week',
        'start_time',
        'end_time',
        'note',
    ];

    protected $casts = [
        'start_time' => 'datetime:H:i',
        'end_time'   => 'datetime:H:i',
        'day_of_week' => 'integer',
    ];
    protected $rules = [
        'day_of_week' => 'nullable|integer|between:1,7',
        'start_time' => 'nullable|date_format:H:i:s|before:end_time',
        'end_time' => 'nullable|date_format:H:i:s|after:start_time',
    ];
    public function instructor()
    {
        return $this->belongsTo(Instructor::class, 'instructor_id');
    }

    public function getDayOfWeekLabelAttribute(): ?string
    {
        return is_null($this->day_of_week)
            ? null
            : \App\Enums\DayOfWeek::from($this->day_of_week)?->getLabel();
    }

    public function isFullDay(): bool
    {
        return is_null($this->start_time) && is_null($this->end_time);
    }

    public function getStartTimeFormattedAttribute(): ?string
    {
        return $this->start_time ? date('H:i', strtotime($this->start_time)) : null;
    }

    public function getEndTimeFormattedAttribute(): ?string
    {
        return $this->end_time ? date('H:i', strtotime($this->end_time)) : null;
    }

    public function scopeForDay($query, int $dayOfWeek)
    {
        return $query->where('day_of_week', $dayOfWeek);
    }

    public function scopeForTimeRange($query, string $startTime, string $endTime)
    {
        return $query->where(function($q) use ($startTime, $endTime) {
            $q->where('start_time', '<=', $startTime)
                ->where('end_time', '>=', $endTime);
        });
    }
}
