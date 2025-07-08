<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;

class Birim extends Model
{
    protected $table = 'dp_birims';
    public $timestamps = true;
    protected $fillable = [
        'name',
        'code',
     ];
    public function scopedManager()
    {
        $roleId = Role::where('name', 'birim_yoneticisi')->first()->id ?? null;
        if (!$roleId) {
            return null; // Rol bulunamazsa boş döneriz
        }

        return $this->belongsToMany(
            User::class,
            config('permission.table_names.model_has_roles'),
            'scope_id', // model_has_roles.scope_id = birim id
            'model_id'  // model_has_roles.model_id = user id
        )
            ->wherePivot('role_id', $roleId)
            ->wherePivot('model_type', User::class)
            ->wherePivot('scope_type', Birim::class);
    }

    public function bolums(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Bolum::class)->orderBy('name');
    }

    public function classrooms(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Classroom::class, 'dp_birim_classrooms', 'birim_id', 'classroom_id')
            ->withTimestamps();
    }

    public function getDisplayNameAttribute(): string
    {
        return collect(explode(' ', $this->name))
            ->filter()
            ->map(fn($word) => mb_substr($word, 0, 1))
            ->join('');
    }
}
