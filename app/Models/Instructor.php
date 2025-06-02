<?php

namespace App\Models;

use App\Enums\DayOfWeek;
use Carbon\Carbon;
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
    public function constraints()
    {
        return $this->hasMany(InstructorConstraint::class, 'instructor_id');
    }
    public function getShortNameAttribute(){

        $firstName = explode(' ', $this->adi)[0] ?? '';
        $lastName = explode(' ', $this->soyadi)[0] ?? '';

        return $firstName . ' ' . mb_substr($lastName, 0, 3);
        return collect(explode(' ', $this->name))
            ->map(fn($word) => mb_substr($word, 0, 3))
            ->implode(' ');

    }
    public function getConstraintsGrouped(): array
    {
        $constraints = $this->constraints()->get();
        $grouped = [];

        foreach ($constraints as $constraint) {
            $day = $constraint->day_of_week;
            if (!isset($grouped[$day])) {
                $grouped[$day] = [];
            }

            $grouped[$day][] = [
                'start_time' => $constraint->start_time,
                'end_time' => $constraint->end_time,
                'is_full_day' => $constraint->isFullDay(),
                'note' => $constraint->note
            ];
        }

        return $grouped;
    }
    public function getConstraintsForDay(int $dayOfWeek): \Illuminate\Database\Eloquent\Collection
    {
        return $this->constraints()->where('day_of_week', $dayOfWeek)->get();
    }

    public function isConstrainedAt(int $dayOfWeek, string $time): bool
    {
        return $this->constraints()
            ->where('day_of_week', $dayOfWeek)
            ->where(function($query) use ($time) {
                $query->where(function($q) use ($time) {
                    // Tam gün kısıtı
                    $q->whereNull('start_time')->whereNull('end_time');
                })->orWhere(function($q) use ($time) {
                    // Belirli saat aralığı kısıtı
                    $q->whereNotNull('start_time')
                        ->whereNotNull('end_time')
                        ->where('start_time', '<=', $time)
                        ->where('end_time', '>=', $time);
                });
            })
            ->exists();
    }
}
