<?php
// app/Traits/HasScopedRoles.php
namespace App\Traits;

use Illuminate\Support\Collection;
use App\Models\Birim;
use App\Models\Bolum;

trait HasScopedRoles
{
    public function scopedBirims(): Collection
    {
        $scoped =  collect($this->roles)
            ->where('pivot.scope_type', Birim::class);
            //->pluck('pivot.scope_id');

        $hasGlobalBirimRole = $this->roles->contains(function ($role) {
            return $role->pivot->scope_type === null || $role->pivot->scope_id === null;
        });

        if ($hasGlobalBirimRole) {
            return Birim::all();
        }

        return $scoped;
    }

    public function scopedBolums(): Collection
    {
        $scoped = collect($this->roles)
            ->where('pivot.scope_type', Bolum::class);
            //->pluck('pivot.scope_id');

        $hasGlobalBirimRole = $this->roles->contains(function ($role) {
            return $role->pivot->scope_type === null || $role->pivot->scope_id === null;
        });

        if ($hasGlobalBirimRole) {
            return Bolum::all();
        }

        return $scoped;
    }

    public function scopedUnits(): Collection
    {
        return $this->scopedBirims()->merge($this->scopedBolums())->filter();
    }
    public function scopedInstructors()
    {
        $birimInstructors = \App\Models\Instructor::whereHas('courseClasses.program.bolum.birim', function ($query) {
            $query->whereIn('birim_id', $this->scopedBirims()->pluck('id'));
        });

        $bolumInstructors = \App\Models\Instructor::whereHas('courseClasses.program.bolum', function ($query) {
            $query->whereIn('bolum_id', $this->scopedBolums()->pluck('id'));
        });

        return $birimInstructors->union($bolumInstructors)->get();
    }
}
