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
}
