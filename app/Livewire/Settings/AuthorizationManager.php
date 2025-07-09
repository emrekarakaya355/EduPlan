<?php

namespace App\Livewire\Settings;

use Livewire\Component;
use App\Models\Birim;
use App\Models\Bolum;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;

class AuthorizationManager extends Component
{
    // Public properties to be used in the Blade view
    public $birims;
    public $bolums;
    public $users;
    public $roles;

    // State for scope selection (Unit or Department)
    public $selectedEntityType = 'birim';
    public $selectedEntityId = null;

    protected $listeners = [
        'roleAssigned' => 'handleRoleAssigned'
    ];

    /**
     * Component's initial setup.
     * Fetches all necessary data from the database.
     */
    public function mount()
    {
        $this->refreshData();
    }

    /**
     * Refreshes all data from the database.
     */
    public function refreshData()
    {
        $this->birims = Birim::all();
        $this->bolums = Bolum::all();
        /*
        $this->users = User::all();
        $this->roles = Role::all();
*/
        // Set initial selected entity to the first Birim if available
        if (!$this->selectedEntityId) {
            $firstBirim = $this->birims->first();
            if ($firstBirim) {
                $this->selectedEntityId = $firstBirim->id;
            } else {
                // If no birims, try to select the first bolum
                $firstBolum = $this->bolums->first();
                if ($firstBolum) {
                    $this->selectedEntityType = 'bolum';
                    $this->selectedEntityId = $firstBolum->id;
                }
            }
        }
    }

    /**
     * Handles changing the scope type (Birim or Bolum).
     */
    public function selectScopeType($type)
    {
        $this->selectedEntityType = $type;
        // Reset selected ID to the first item of the new type or null
        if ($type === 'birim') {
            $this->selectedEntityId = $this->birims->first()->id ?? null;
        } else {
            $this->selectedEntityId = $this->bolums->first()->id ?? null;
        }
    }

    /**
     * Handles selecting a specific Birim or Bolum.
     */
    public function selectEntity($id)
    {
        $this->selectedEntityId = (int) $id;
    }

    /**
     * Opens the role assignment modal via event dispatch.
     */
    public function openRoleModal($userId = null)
    {
        $this->dispatch('openRoleModal', $userId);
    }

    /**
     * Handles the role assignment completion event from the modal.
     */
    public function handleRoleAssigned($data)
    {
        $this->refreshData();
        session()->flash('message', $data['message']);
    }

    /**
     * Computed property to get departments filtered by the selected unit.
     */
    public function getFilteredBolumsProperty()
    {
        if ($this->selectedEntityType === 'birim' && $this->selectedEntityId) {
            return $this->bolums->where('birim_id', $this->selectedEntityId);
        }
        return $this->bolums;
    }

    /**
     * Computed property to get users who have roles within the currently selected scope.
     */
    public function getScopedUsersProperty()
    {
        if (!$this->selectedEntityId) {
            return collect();
        }

        $scopeModelClass = $this->selectedEntityType === 'birim' ? Birim::class : Bolum::class;

        $usersInScope = User::whereHas('roles', function ($query) use ($scopeModelClass) {
            $query->where(config('permission.table_names.model_has_roles') . '.scope_type', $scopeModelClass)
                ->where(config('permission.table_names.model_has_roles') . '.scope_id', $this->selectedEntityId);
        })->get();

        return $usersInScope->map(function ($user) use ($scopeModelClass) {
            $user->current_scope_role = $user->roles->first(function($role) use ($scopeModelClass, $user) {
                return $role->pivot->model_id === $user->id &&
                    $role->pivot->model_type === User::class &&
                    $role->pivot->scope_type === $scopeModelClass &&
                    $role->pivot->scope_id == $this->selectedEntityId;
            });
            return $user;
        });
    }

    /**
     * Renders the component's view.
     */
    public function render()
    {
        return view('livewire.settings.authorization-manager');
    }
}
