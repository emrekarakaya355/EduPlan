<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Birim extends Model
{
    protected $table = 'dp_birims';
    public $timestamps = true;
    protected $fillable = [
        'name',
        'code',
    ];

    public function bolums(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Bolum::class)->orderBy('name');
    }

    public function classrooms(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Classroom::class, 'dp_birim_classrooms', 'birim_id', 'classroom_id')
            ->withTimestamps();
    }

    public function getDisplayNameAttribute(): string
    {
        return collect(explode(' ', $this->name))
            ->filter()
            ->map(fn($word) => mb_substr($word, 0, 1))
            ->join('');
    }
}
