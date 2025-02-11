<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Instructor extends Model
{
    protected $table = 'kimlik';

    public function courseClasses(){
        return $this->hasMany(Course_class::class);
    }
}
