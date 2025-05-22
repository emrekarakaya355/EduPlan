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

    public function getShortNameAttribute(){

        $firstName = explode(' ', $this->adi)[0] ?? '';
        $lastName = explode(' ', $this->soyadi)[0] ?? '';

        return $firstName . ' ' . mb_substr($lastName, 0, 3);
        return collect(explode(' ', $this->name))
            ->map(fn($word) => mb_substr($word, 0, 3))
            ->implode(' ');

    }
}
