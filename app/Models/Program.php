<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    protected $table = 'dp_programs';
    public $timestamps = true;
    protected $fillable = [
        'name',
    ];


    public function bolum(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Bolum::class);
    }

    public function courseClasses(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Course_class::class, 'program_id', 'id');  // CourseClass'lar
    }


}
