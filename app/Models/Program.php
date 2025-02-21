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



    public function schedules(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Schedule::class, 'program_id', 'id');
    }
    public function getNameAttribute($value)
    {
/*
        $parts = explode(' - ', $value);

         $temp = $parts[0] ?? $value;
        $programPart = $parts[1] ?? $value;
        $programName = explode(' ',explode('(', $programPart)[0]);
        $programNameShort = "";
        if(count($programName) > 1){
            foreach ($programName as $word) {
                $programNameShort .= strtoupper(substr($word, 0, 1));
            }
        }else{
            $programNameShort = $programName[0];
        }

        if(str_contains('İKİNCİ ÖĞRETİM', $value)) {
            $programNameShort.= ' İO';
        }
*/
        $formattedName = preg_replace('/Birinci Öğretim -/', '', $value);
        $formattedName = preg_replace('/İkinci Öğretim -/', '', $formattedName);
        $formattedName = preg_replace('/İKİNCİ ÖĞRETİM/', 'İO', $formattedName);

        // Boşlukları düzenleyip formatı uygun hale getir
        $formattedName = preg_replace('/\s+/', ' ', trim($formattedName));
        // Son olarak, dönüşen program adını döndür
        return $formattedName;
    }

}
