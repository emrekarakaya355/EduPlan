<?php

namespace App\Traits;
use App\Models\Birim;
use App\Models\Bolum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles as SpatieHasRoles;

trait UserHasScopedRoles
{
    use SpatieHasRoles {
        SpatieHasRoles::hasRole as spatieHasRole;
        SpatieHasRoles::hasPermissionTo as spatieHasPermissionTo;
        SpatieHasRoles::roles as spatieRoles;
        SpatieHasRoles::permissions as spatiePermissions;
    }

    /**
     * Kullanıcının belirli bir role, belirli bir kapsamda sahip olup olmadığını kontrol eder.
     * @param string|array|Role|\Illuminate\Support\Collection $roles Rol(ler)in adı/ID'si
     * @param Model|null $scope Kapsam modeli (Birim, Bolum veya null for global/any)
     * @param string|null $guard Guard adı
     * @return bool
     */
    public function hasRole($roles, ?Model $scope = null, ?string $guard = null): bool
    {
        // Admin her zaman true döner
        if ($this->spatieHasRole('admin', $guard)) {
            return true;
        }

        // Eğer rol isimleri bir dizi veya koleksiyon ise, hepsi için kontrol et
        $roles = collect($roles)->map(function ($role) {
            return $role instanceof Role ? $role->name : $role;
        });

        if ($scope) {
            if ($this->roles()->whereIn('name', $roles)
                ->wherePivot('scope_id', $scope->id)
                ->wherePivot('scope_type', $scope::class)
                ->exists()) {
                return true;
            }
        } else {
            // Eğer kapsam belirtilmemişse, global rolleri kontrol et
            if ($this->roles()->whereIn('name', $roles)
                ->wherePivot('scope_id', null)
                ->wherePivot('scope_type', null)
                ->exists()) {
                return true;
            }
        }

        // 2. Hiyerarşik Kapsam Kontrolü (Örn: Birim Admini, altındaki bölüm için yetkili mi?)
        if ($scope instanceof Bolum) {
            $birim = $scope->birim;
            if ($birim && $this->hasRole($roles, $birim, $guard)) {
                // Eğer kullanıcı birim kapsamında bu role sahipse, altındaki bölümler için de geçerli say
                // Ancak bu, yalnızca 'unit_admin' gibi bir hiyerarşik rol için geçerli olmalı.
                // Eğer rol 'editor' ise, birim seviyesinde atanan editor'ün tüm bölümleri editleyebilmesi lazım.
                // Bu kısım policy'lerde daha spesifik olarak ele alınmalı.
                // Şimdilik, eğer rol 'unit_admin' ise ve birim kapsamında ise direkt izin verelim.
                if ($roles->contains('unit_admin')) { // Eğer aranılan rol 'unit_admin' ise
                    return true;
                }
            }
        }

        // Eğer belirli bir scope verilmemişse veya yukarıdaki kontroller başarısız olursa,
        // Spatie'nin genel hasRole metodunu kontrol et (global rolleri için).
        return $this->spatieHasRole($roles, $guard);
    }

    /**
     * Kullanıcının belirli bir izne, belirli bir kapsamda sahip olup olmadığını kontrol eder.
     * @param string|Permission|\Illuminate\Support\Collection $permission İzin(ler)in adı/objesi
     * @param Model|null $scope Kapsam modeli (Birim, Bolum veya null for global/any)
     * @param string|null $guard Guard adı
     * @return bool
     */
    public function hasPermissionTo($permission, ?Model $scope = null, ?string $guard = null): bool
    {
        if ($this->spatieHasRole('admin', $guard)) {
            return true;
        }

        $permission = $this->getPermissionClass()->findByName($permission, $guard ?? config('auth.defaults.guard'));
        if (!$permission) {
            return false;
        }

        // 1. Doğrudan Kapsamlı İzin Kontrolü (permission_has_models)
        if ($scope) {
            if ($this->permissions()->where('id', $permission->id)
                ->wherePivot('scope_id', $scope->id)
                ->wherePivot('scope_type', $scope::class)
                ->exists()) {
                return true;
            }
            // Kullanıcının bu kapsamda sahip olduğu rollerden, bu izni içeren var mı?
            if ($this->roles()
                ->wherePivot('scope_id', $scope->id)
                ->wherePivot('scope_type', $scope::class)
                ->whereHas('permissions', fn (Builder $query) => $query->where('id', $permission->id))
                ->exists()) {
                return true;
            }
        } else {
            if ($this->permissions()->where('id', $permission->id)
                ->wherePivot('scope_id', null)
                ->wherePivot('scope_type', null)
                ->exists()) {
                return true;
            }
            if ($this->roles()->wherePivot('scope_id', null)
                ->wherePivot('scope_type', null)
                ->whereHas('permissions', fn (Builder $query) => $query->where('id', $permission->id))
                ->exists()) {
                return true;
            }
        }

        // 2. Hiyerarşik İzin Kontrolü (Örn: Birim admini, altındaki bölümün izinlerine sahip mi?)
        if ($scope instanceof Bolum) {
            $birim = $scope->birim; // Bölümün ait olduğu birimi al
            if ($birim) {
                // Eğer kullanıcı Birim seviyesinde 'unit_admin' ise, o birimin tüm izinlerine sahip olmalı.
                // Veya birim seviyesinde ilgili izne sahipse (örn. 'edit schedules' birim seviyesinde atanmışsa).
                if ($this->hasRole('unit_admin', $birim, $guard)) {
                    // Birim admini ise, o birimin altındaki her şey için ilgili izinlere sahip olmalı
                    return true;
                }
                // Kullanıcının birim bazında, bu izni içeren bir rolü var mı?
                if ($this->roles()
                    ->wherePivot('scope_id', $birim->id)
                    ->wherePivot('scope_type', Birim::class)
                    ->whereHas('permissions', fn (Builder $query) => $query->where('id', $permission->id))
                    ->exists()) {
                    return true;
                }
            }
        }

        // Eğer belirli bir scope verilmemişse veya yukarıdaki kontroller başarısız olursa,
        // Spatie'nin genel hasPermissionTo metodunu kontrol et (global izinleri için).
        return $this->spatieHasPermissionTo($permission, $guard);
    }

    // Spatie'nin kendi ilişkilerini özelleştirerek pivot verilerini çekmek
    // Bu, pivot tablodan scope_id ve scope_type'ı doğru bir şekilde çekmek için kritik.
    public function roles(): \Illuminate\Database\Eloquent\Relations\MorphToMany
    {
        return $this->morphToMany(
            config('permission.models.role'),
            'model',
            config('permission.table_names.model_has_roles'),
            config('permission.column_names.model_morph_key'),
            config('permission.column_names.role_pivot_key')
        )->withPivot('scope_id', 'scope_type'); // <-- scope_id ve scope_type'ı çekmeyi unutmayın
    }

    public function permissions(): \Illuminate\Database\Eloquent\Relations\MorphToMany
    {
        return $this->morphToMany(
            config('permission.models.permission'),
            'model',
            config('permission.table_names.model_has_permissions'),
            config('permission.column_names.model_morph_key'),
            config('permission.column_names.permission_pivot_key')
        )->withPivot('scope_id', 'scope_type'); // <-- scope_id ve scope_type'ı çekmeyi unutmayın
    }

    /**
     * Kullanıcının herhangi bir rol veya izin kapsamında yetkili olduğu birimlerin koleksiyonunu döndürür.
     * UI'daki birim filtresi için kullanılacak.
     */
    public function getAuthorizedBirims()
    {
        // Admin ise tüm birimleri getir
        if ($this->hasRole('admin')) {
            return Birim::all();
        }

        $authorizedBirims = collect();

        // 1. Kullanıcının doğrudan birim yöneticisi olduğu birimler
        $authorizedBirims = $authorizedBirims->merge($this->managedUnits);

        // 2. Kullanıcının sahip olduğu birim kapsamlı rollerden gelen birimler
        $this->roles()->wherePivot('scope_type', Birim::class)->get()->each(function ($role) use ($authorizedBirims) {
            $birim = Birim::find($role->pivot->scope_id);
            if ($birim) {
                $authorizedBirims->push($birim);
            }
        });

        // 3. Kullanıcının sahip olduğu bölüm kapsamlı rollerin üst birimleri (eğer birim seviyesinde erişim de gerekiyorsa)
        // Eğer bir bölümün yöneticisiyse, o bölümün birimini de görebilmesi mantıklı olabilir.
        $this->roles()->wherePivot('scope_type', Bolum::class)->get()->each(function ($role) use ($authorizedBirims) {
            $bolum = Bolum::find($role->pivot->scope_id);
            if ($bolum && $bolum->birim) {
                $authorizedBirims->push($bolum->birim);
            }
        });

        // 4. Doğrudan izinlerden gelen birimler (eğer doğrudan birim bazlı izinler atanıyorsa)
        $this->permissions()->wherePivot('scope_type', Birim::class)->get()->each(function ($permission) use ($authorizedBirims) {
            $birim = Birim::find($permission->pivot->scope_id);
            if ($birim) {
                $authorizedBirims->push($birim);
            }
        });


        return $authorizedBirims->filter()->unique('id');
    }

    /**
     * Kullanıcının herhangi bir rol veya izin kapsamında yetkili olduğu bölümlerin koleksiyonunu döndürür.
     * UI'daki bölüm filtresi için kullanılacak.
     */
    public function getAuthorizedBolums()
    {
        // Admin ise tüm bölümleri getir
        if ($this->hasRole('admin')) {
            return Bolum::all();
        }

        $authorizedBolums = collect();

        // 1. Kullanıcının doğrudan bölüm yöneticisi olduğu bölümler
        $authorizedBolums = $authorizedBolums->merge($this->managedDepartments);

        // 2. Kullanıcının sahip olduğu bölüm kapsamlı rollerden gelen bölümler
        $this->roles()->wherePivot('scope_type', Bolum::class)->get()->each(function ($role) use ($authorizedBolums) {
            $bolum = Bolum::find($role->pivot->scope_id);
            if ($bolum) {
                $authorizedBolums->push($bolum);
            }
        });

        // 3. Kullanıcının birim yöneticisi olduğu birimlerin altındaki tüm bölümler
        $this->getAuthorizedBirims()->each(function ($birim) use ($authorizedBolums) {
            if ($this->hasRole('unit_admin', $birim)) { // Eğer birim admini ise, altındaki tüm bölümlere yetkilidir.
                $birim->bolums->each(function ($bolum) use ($authorizedBolums) {
                    $authorizedBolums->push($bolum);
                });
            }
        });

        // 4. Doğrudan izinlerden gelen bölümler (eğer doğrudan bölüm bazlı izinler atanıyorsa)
        $this->permissions()->wherePivot('scope_type', Bolum::class)->get()->each(function ($permission) use ($authorizedBolums) {
            $bolum = Bolum::find($permission->pivot->scope_id);
            if ($bolum) {
                $authorizedBolums->push($bolum);
            }
        });

        return $authorizedBolums->filter()->unique('id');
    }

}
