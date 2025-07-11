<?php

namespace App\Livewire\Settings;

use Livewire\Component;
use App\Models\Birim;
use App\Models\Bolum;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class AuthorizationManager extends Component
{
    // State
    public $birims;
    public Collection $bolumsOfSelectedBirim;

    public $selectedBirimId = null;
    public $selectedBolumId = null; // null veya '' ise "Tümü" anlamına gelir.

    protected $listeners = [
        'roleAssigned' => '$refresh' // Rol atanınca tüm bileşeni yenile
    ];

    public function mount()
    {
        $this->birims = Birim::orderBy('name')->get();
        if ($this->birims->isNotEmpty()) {
            $this->selectedBirimId = $this->birims->first()->id;
            $this->loadBolumsForSelectedBirim();
        } else {
            $this->bolumsOfSelectedBirim = new Collection();
        }
    }

    public function updatedSelectedBirimId()
    {
        $this->loadBolumsForSelectedBirim();
        $this->selectedBolumId = null; // Birim değişince bölüm filtresini sıfırla
    }

    public function loadBolumsForSelectedBirim()
    {
        $this->bolumsOfSelectedBirim = $this->selectedBirimId
            ? Bolum::where('birim_id', $this->selectedBirimId)->orderBy('name')->get()
            : new Collection();
    }

    /**
     * Rol düzenleme/silme modalını belirli bir kullanıcı ve kapsam için açar.
     */
    public function openRoleModal($userId, $scopeType, $scopeId)
    {
        $this->dispatch('openRoleModal', [
            'userId' => $userId,
            'selectedEntityType' => $scopeType,
            'selectedEntityId' => $scopeId,
        ]);
    }

    /**
     * Yeni bir rol atamak için modal'ı açar.
     * Modal'a kapsam seçebilmesi için gerekli bilgileri gönderir.
     */
    public function openNewRoleModal()
    {
        $scopes = $this->bolumsOfSelectedBirim->map(function($bolum) {
            return ['type' => 'bolum', 'id' => $bolum->id, 'name' => $bolum->name];
        })->prepend(
            ['type' => 'birim', 'id' => $this->selectedBirimId, 'name' => $this->birims->find($this->selectedBirimId)->name . ' (Birim Geneli)']
        );

        $this->dispatch('openRoleModal', [
            'userId' => null, // Yeni atama olduğu için userId null
            'availableScopes' => $scopes,
        ]);
    }

    public function getScopedUsersProperty()
    {
        if (!$this->selectedBirimId) return collect();

        $bolumIds = $this->bolumsOfSelectedBirim->pluck('id');

        $allUsersInScope = User::whereHas('roles', function ($query) use ($bolumIds) {
            $query->where(function($q) use ($bolumIds) {
                $q->where('scope_type', Birim::class)
                    ->where('scope_id', $this->selectedBirimId);
            })->orWhere(function($q) use ($bolumIds) {
                $q->where('scope_type', Bolum::class)
                    ->whereIn('scope_id', $bolumIds);
            });
        })
            ->with(['roles' => function($query) use ($bolumIds) {
                $query->where(function($q) use ($bolumIds) {
                    $q->where('scope_type', Birim::class)
                        ->where('scope_id', $this->selectedBirimId);
                })->orWhere(function($q) use ($bolumIds) {
                    $q->where('scope_type', Bolum::class)
                        ->whereIn('scope_id', $bolumIds);
                });
            }])
            ->get();

        // Veriyi Blade'de kolay kullanmak için işle
        $allUsersInScope->each(function ($user) {
            $user->displayRoles = $user->roles->map(function ($role) {
                $scopeModel = $role->pivot->scope_type::find($role->pivot->scope_id);
                return [
                    'role_name' => $role->name,
                    'scope_name' => $scopeModel->name ?? 'Bilinmeyen Kapsam',
                    'scope_type' => $role->pivot->scope_type === Birim::class ? 'Birim' : 'Bölüm',
                    'scope_type_class' => $role->pivot->scope_type, // Modalı tetiklemek için
                    'scope_id' => $role->pivot->scope_id,             // Modalı tetiklemek için
                ];
            });
        });

        // SONRA FİLTRELE: Eğer bir bölüm filtresi seçilmişse, listeyi filtrele
        if (!empty($this->selectedBolumId)) {
            return $allUsersInScope->filter(function ($user) {
                // Kullanıcının rollerinden en az biri seçili bölümle eşleşiyorsa göster
                return $user->displayRoles->contains(fn($role) =>
                    $role['scope_type_class'] === Bolum::class && $role['scope_id'] == $this->selectedBolumId
                );
            });
        }

        // Filtre yoksa tümünü döndür
        return $allUsersInScope;
    }

    public function render()
    {
        return view('livewire.settings.authorization-manager');
    }
}
