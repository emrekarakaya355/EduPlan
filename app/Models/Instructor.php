<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Instructor extends Model
{
    protected $table = 'kimlik';

    public function courseClasses(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Course_class::class,'instructorId','id');
    }

    public function getNameAttribute(){
        return $this->adi . ' ' . $this->soyadi. ' ';
    }
}
