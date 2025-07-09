<div class="p-6 bg-white rounded-lg shadow-sm min-h-[calc(100vh-4rem-16px)]">
    <h3 class="text-2xl font-semibold text-gray-800 mb-6">Yetkilendirme Yöneticisi</h3>

    {{-- Session Flash Messages --}}
    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('message') }}</span>
        </div>
    @endif
    @if (session()->has('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    {{-- Kapsam Seçimi --}}
    <div class="flex flex-col md:flex-row gap-4 mb-6 items-center">
        <div class="flex space-x-2 bg-gray-100 rounded-lg p-1">
            <button
                wire:click="selectScopeType('birim')"
                class="px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200 {{ $selectedEntityType === 'birim' ? 'bg-blue-600 text-white shadow' : 'text-gray-700 hover:bg-gray-200' }}"
            >
                Birimler
            </button>
            <button
                wire:click="selectScopeType('bolum')"
                class="px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200 {{ $selectedEntityType === 'bolum' ? 'bg-blue-600 text-white shadow' : 'text-gray-700 hover:bg-gray-200' }}"
            >
                Bölümler
            </button>
        </div>

        <div class="flex-1 w-full md:w-auto">
            <label for="scope-select" class="sr-only">Kapsam Seç</label>
            <select
                id="scope-select"
                wire:model.live="selectedEntityId"
                class="block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
            >
                @if ($selectedEntityType === 'birim')
                    @foreach($birims as $birim)
                        <option value="{{ $birim->id }}">{{ $birim->name }}</option>
                    @endforeach
                @else
                    @foreach($this->filteredBolums as $bolum)
                        <option value="{{ $bolum->id }}">{{ $bolum->name }} ({{ $birims->firstWhere('id', $bolum->birim_id)?->name }})</option>
                    @endforeach
                @endif
                @if (!$selectedEntityId)
                    <option value="" disabled>Lütfen bir {{ $selectedEntityType === 'birim' ? 'birim' : 'bölüm' }} seçin</option>
                @endif
            </select>
        </div>
    </div>

    {{-- Seçili Kapsamdaki Kullanıcı Listesi --}}
    @if ($selectedEntityId)
        <div class="overflow-x-auto bg-gray-50 rounded-lg p-4">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider rounded-tl-lg">
                        Kullanıcı Adı
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Email
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Kapsam Rolü
                    </th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider rounded-tr-lg">
                        Eylemler
                    </th>
                </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                @forelse($this->scopedUsers as $user)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $user->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $user->email }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $user->current_scope_role?->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <button
                                wire:click="openRoleModal({{ $user->id }})"
                                class="text-blue-600 hover:text-blue-900 font-medium py-1 px-3 rounded-md border border-blue-600 hover:bg-blue-50 transition-colors duration-200"
                            >
                                Rolü Değiştir
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                            Bu {{ $selectedEntityType === 'birim' ? 'birimde' : 'bölümde' }} atanmış kullanıcı bulunmamaktadır.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>

            <div class="mt-4 text-right">
                <button
                    wire:click="openRoleModal()"
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
                >
                    Yeni Rol Ata
                </button>
            </div>
        </div>
    @else
        <p class="text-center text-gray-500 mt-8">Lütfen yukarıdan bir birim veya bölüm seçin.</p>
    @endif

    @if($selectedEntityId)

        <livewire:settings.role-modal :$selectedEntityType :$selectedEntityId />
    @endif
</div>
