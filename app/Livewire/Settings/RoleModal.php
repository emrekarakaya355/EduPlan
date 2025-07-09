<?php

namespace App\Livewire\Settings;

use App\Models\Birim; // Add this import
use App\Models\Bolum; // Add this import
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class RoleModal extends Component
{
    public $showModal = false;
    // public $users; // Remove this property, we'll load them on demand
    public $roles; // Keep roles, as there are likely fewer of them

    public $selectedEntityType;
    public $selectedEntityId;
    public $userToEditId = null;

    // Modal state
    public $selectedUserForModal = '';
    public $selectedRoleForModal = '';

    protected $listeners = [
        'openRoleModal' => 'openModal',
        'closeRoleModal' => 'closeModal'
    ];

    public function mount()
    {
        // Load roles here, as they are generally fewer and static
        $this->roles = Role::all();
    }

    /**
     * Opens the role assignment modal.
     */
    public function openModal($userId = null, $entityType = null, $entityId = null)
    {
        // Set the scope data from the parent component
        $this->selectedEntityType = $entityType;
        $this->selectedEntityId = $entityId;

        $this->userToEditId = $userId;
        $this->selectedUserForModal = $userId;
        $this->selectedRoleForModal = '';
        $this->showModal = true;

        // Reset validation errors when opening the modal
        $this->resetValidation();

        // If editing, try to pre-fill the role for the current scope
        if ($userId) {
            $user = User::find($userId); // Fetch only the specific user if needed
            if ($user) {
                $scopeModelClass = $this->selectedEntityType === 'birim' ? Birim::class : Bolum::class;

                $currentRole = DB::table(config('permission.table_names.model_has_roles'))
                    ->where('model_id', $user->id)
                    ->where('model_type', User::class)
                    ->where('scope_id', $this->selectedEntityId)
                    ->where('scope_type', $scopeModelClass)
                    ->first();
                if ($currentRole) {
                    $this->selectedRoleForModal = $currentRole->role_id;
                }
            }
        }
    }

    /**
     * Closes the role assignment modal and resets its state.
     */
    public function closeModal()
    {
        $this->showModal = false;
        $this->userToEditId = null;
        $this->selectedUserForModal = '';
        $this->selectedRoleForModal = '';
        $this->resetValidation(); // Also reset validation on close
    }

    /**
     * Assigns or updates a role for a user within the selected scope.
     */
    public function assignRole()
    {
        $this->validate([
            'selectedUserForModal' => 'required|exists:users,id',
            'selectedRoleForModal' => 'required|exists:roles,id',
            'selectedEntityId' => 'required|integer', // This must be passed from parent
            'selectedEntityType' => 'required|in:birim,bolum', // This must be passed from parent
        ], [
            'selectedUserForModal.required' => 'Lütfen bir kullanıcı seçin.',
            'selectedUserForModal.exists' => 'Seçilen kullanıcı geçersiz.',
            'selectedRoleForModal.required' => 'Lütfen bir rol seçin.',
            'selectedRoleForModal.exists' => 'Seçilen rol geçersiz.',
            'selectedEntityId.required' => 'Kapsam seçimi zorunludur.',
            'selectedEntityType.required' => 'Kapsam türü zorunludur.',
            'selectedEntityType.in' => 'Geçersiz kapsam türü.',
        ]);

        $user = User::find($this->selectedUserForModal);
        $role = Role::find($this->selectedRoleForModal);

        $scopeModelClass = $this->selectedEntityType === 'birim' ? Birim::class : Bolum::class;

        // Check if the user already has a role for this specific scope
        $existingPivot = DB::table(config('permission.table_names.model_has_roles'))
            ->where('model_id', $user->id)
            ->where('model_type', User::class)
            ->where('scope_id', $this->selectedEntityId)
            ->where('scope_type', $scopeModelClass)
            ->first();

        try {
            if ($existingPivot) {
                // Update existing role
                DB::table(config('permission.table_names.model_has_roles'))
                    ->where('model_id', $user->id)
                    ->where('model_type', User::class)
                    ->where('scope_id', $this->selectedEntityId)
                    ->where('scope_type', $scopeModelClass)
                    ->update(['role_id' => $role->id, 'updated_at' => now()]);

                $message = 'Kullanıcının rolü güncellendi.';
            } else {
                // Assign new role
                DB::table(config('permission.table_names.model_has_roles'))->insert([
                    'role_id' => $role->id,
                    'model_type' => User::class,
                    'model_id' => $user->id,
                    'scope_id' => $this->selectedEntityId,
                    'scope_type' => $scopeModelClass,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $message = 'Kullanıcıya yeni rol atandı.';
            }

            // Emit event to parent to refresh data and show success message
            $this->dispatch('roleAssigned', ['message' => $message]);
            $this->closeModal();

        } catch (\Exception $e) {
            session()->flash('error', 'Rol atama/güncelleme sırasında bir hata oluştu: ' . $e->getMessage());
        }
    }

    public function render()
    {


        $users = $this->showModal ? \Auth::user()->scopedInstructors() :collect();

        return view('livewire.settings.role-modal', [
            'users' => $users,
            'roles' => $this->roles,
        ]);
    }
}
