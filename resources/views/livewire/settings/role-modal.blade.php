<div>
    @if ($showModal)
    <div class="fixed inset-0 bg-gray-600 bg-opacity-75 flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md transform transition-all sm:my-8 sm:align-middle sm:w-full">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                {{ $userToEditId ? 'Rolü Değiştir' : 'Yeni Rol Ata' }}
            </h3>

            <div class="space-y-4">
                <div>
                    <label for="user-select-modal" class="block text-sm font-medium text-gray-700">
                        Kullanıcı Seç
                    </label>
                    <select
                        id="user-select-modal"
                        wire:model="selectedUserForModal"
                        {{ $userToEditId ? 'disabled' : '' }}
                        class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm {{ $userToEditId ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                    >
                        <option value="">Kullanıcı Seçin</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                    @error('selectedUserForModal')
                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="role-select-modal" class="block text-sm font-medium text-gray-700">
                        Rol Seç
                    </label>
                    <select
                        id="role-select-modal"
                        wire:model="selectedRoleForModal"
                        class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                    >
                        <option value="">Rol Seçin</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                        @endforeach
                    </select>
                    @error('selectedRoleForModal')
                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="mt-6 flex justify-end space-x-3">
                <button
                    type="button"
                    wire:click="closeModal"
                    class="inline-flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >
                    İptal
                </button>
                <button
                    type="button"
                    wire:click="assignRole"
                    class="inline-flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                >
                    {{ $userToEditId ? 'Rolü Güncelle' : 'Rol Ata' }}
                </button>
            </div>
        </div>
    </div>
@endif
</div>
