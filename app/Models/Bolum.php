<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;

class Bolum extends Model
{
    protected $table = 'dp_bolums';
    public $timestamps = true;
    protected $fillable = [
        'name',
        'birim_id',
     ];

    public function scopedManager()
    {
        $roleId = Role::where('name', 'bolum_sorumlusu')->first()->id ?? null;
        if (!$roleId) {
            return null; // Rol bulunamazsa addEagerLoading NUll Hatası veriyor!
        }

        return $this->belongsToMany(
            User::class,
            config('permission.table_names.model_has_roles'),
            'scope_id', // model_has_roles.scope_id = birim id
            'model_id'  // model_has_roles.model_id = user id
        )
            ->wherePivot('role_id', $roleId)
            ->wherePivot('model_type', User::class)
            ->wherePivot('scope_type', Bolum::class);
    }
    public function birim(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Birim::class);
    }

    public function programs(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Program::class)->orderBy('name');
    }
    public function getInstructors()
    {
        return $this?->programs?->instructors;
    }
}
