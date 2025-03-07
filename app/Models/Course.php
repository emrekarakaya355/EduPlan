<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $table = 'dp_courses';
    public $timestamps = true;
    protected $fillable = [
        'name',
        'code',
        'external_id',
    ];

    public function courseClasses(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Course_class::class, 'course_id', 'id');
    }



}
