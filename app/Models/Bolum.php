<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bolum extends Model
{
    protected $table = 'dp_bolums';
    public $timestamps = true;
    protected $fillable = [
        'name',
        'birim_id',
        'manager_id'
    ];

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }
    public function birim(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Birim::class);
    }

    public function programs(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Program::class)->orderBy('name');
    }
}
