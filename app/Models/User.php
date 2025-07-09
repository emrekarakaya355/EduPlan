<?php

namespace App\Models;

use App\Traits\HasScopedRoles;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Models\Role;

class User extends Authenticatable
{
    protected $table = 'kimlik';

    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasScopedRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'adi',
        'soyadi',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getNameAttribute(): string
    {
        return $this->adi. ' '.$this->soyadi;
    }
    public function roles(): MorphToMany
    {
        return $this->morphToMany(
            Role::class,
            'model',
            config('permission.table_names.model_has_roles'),
            config('permission.column_names.model_morph_key'),
            'role_id'
        )->withPivot('scope_id', 'scope_type');
    }
    public function isAdmin(): bool
    {
        return $this->hasRole('admin') || $this->hasRole('super-admin');
    }

    /*public function managedUnits(): array
    {
        $allowedBirimIds = collect();
        $allowedBolumIds = collect();

        foreach ($this->roles as $role) {
            if($role->pivot && !$role->pivot->scope_id && !$role->pivot->scope_type){
                return [
                    'birimler' => Birim::all(),
                    'bolumler' => Bolum::all(),
                ];
            }
            if ($role->pivot && $role->pivot->scope_id && $role->pivot->scope_type) {

                $scopeType = $role->pivot->scope_type;
                $scopeId = $role->pivot->scope_id;

                if ($scopeType === Birim::class) {
                    $allowedBirimIds->push($scopeId);
                } elseif ($scopeType === Bolum::class) {
                    $allowedBolumIds->push($scopeId);
                }
            }
        }
        $managedBirims = Birim::whereIn('id', $allowedBirimIds->unique()->filter())->get();
        $managedBolums = Bolum::whereIn('id', $allowedBolumIds->unique()->filter())->get();
        return [
            'birimler' => $managedBirims,
            'bolumler' => $managedBolums,
        ];
    }
    public function managedScopes()
    {
        $tableNames = config('permission.table_names'); // Spatie tablo adlarını al

        return $this->hasManyThrough(
            ManagedScope::class, // Nihai olarak ulaşmak istediğimiz model
            Role::class,         // Üzerinden geçeceğimiz model (Spatie'nin Role modeli)
            'id',                // Spatie'nin `roles` tablosundaki primary key (Role modelinin ID'si)
            'scope_id',          // `managed_scopes` tablosundaki (ManagedScope) foreign key (yani ManagedScope'un ID'si)
            'id',                // `users` tablosundaki (User) local key (yani User'ın ID'si)
        // 'role_id' // Bu parametre HasManyThrough için genelde gereksiz veya yanlış anlaşılır.
        // Spatie'nin model_has_roles tablosu User'ı role_id ve model_id ile bağlıyor.
        // Aslında burada Spatie'nin pivot tablosundaki "role_id" ve "model_id" sütunlarını hedeflememiz gerekiyor.
        );
    }
    public function getManagedInstructors(string $searchTerm = null, int $limit = 20): Collection
    {
        if ($this->isAdmin()) {
            return \Cache::remember('all_birims_bolums', 60 * 60, function () {
                return [
                    'birimler' => Birim::all(),
                    'bolumler' => Bolum::all(),
                ];
            });
        }

        $managedUnits = $this->managedUnits();
        $allowedBirimIds = $managedUnits['birimler']->pluck('id');
        $allowedBolumIds = $managedUnits['bolumler']->pluck('id');

        // Eğer hiç yetkili birim veya bölüm yoksa, boş bir koleksiyon döndür
        if ($allowedBirimIds->isEmpty() && $allowedBolumIds->isEmpty()) {
            return collect();
        }

        // Toplanan eğitmen ID'lerine göre User'ları getir
        $instructorsQuery = static::query();

        $instructorsQuery->where(function (Builder $query) use ($allowedBirimIds, $allowedBolumIds) {
            $query->whereHas('taughtCourseClasses', function (Builder $subQuery) use ($allowedBirimIds, $allowedBolumIds) {
                $subQuery->when($allowedBirimIds->isNotEmpty(), function ($q) use ($allowedBirimIds) {
                    $q->orWhereIn('birim_id', $allowedBirimIds);
                })
                    ->when($allowedBolumIds->isNotEmpty(), function ($q) use ($allowedBolumIds) {
                        $q->orWhereIn('bolum_id', $allowedBolumIds);
                    });
            });
        });

        // Arama terimi filtresi
        if ($searchTerm) {
            $instructorsQuery->where(function ($q) use ($searchTerm) {
                $q->where('adi', 'like', '%' . $searchTerm . '%')
                    ->orWhere('soyadi', 'like', '%' . $searchTerm . '%')
                    ->orWhere(\DB::raw('CONCAT(adi, " ", soyadi)'), 'like', '%' . $searchTerm . '%')
                    ->orWhere('email', 'like', '%' . $searchTerm . '%');
            });
        }

        return $instructorsQuery->orderBy('adi')
            ->orderBy('soyadi')
            ->distinct()
            ->limit($limit)
            ->get();
    }*/
}
