<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Campus extends Model
{
    protected $table = 'dp_campuses';
    public $timestamps = true;
    protected $fillable = [
        'name',
    ];

    public function buildings(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Building::class, 'campus_id', 'id');
    }
}
