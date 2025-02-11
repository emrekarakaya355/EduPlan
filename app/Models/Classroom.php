<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    protected $table = 'dp_classrooms';
    public $timestamps = true;
    protected $fillable = [
        'name',
        'capacity',
        'is_lab',
    ];

    public function building(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Building::class);
    }

    public function birims(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Birim::class, 'dp_classroom_birim', 'classroom_id', 'birim_id');
    }
}
