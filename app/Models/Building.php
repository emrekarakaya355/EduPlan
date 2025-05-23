<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Building extends Model
{
    protected $table = 'dp_buildings';
    public $timestamps = true;
    protected $fillable = [
        'name',
        'campus_id'
    ];

    public function campus(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Campus::class);
    }

    public function classrooms(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Classroom::class, 'building_id', 'id');
    }
    public function getDisplayNameAttribute(): string
    {
        return collect(explode(' ', $this->name))
            ->filter()
            ->map(fn($word) => mb_substr($word, 0, 1))
            ->join('');
    }
}
