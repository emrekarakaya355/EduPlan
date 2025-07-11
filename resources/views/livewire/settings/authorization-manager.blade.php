<div class="p-6 bg-white rounded-lg shadow-sm min-h-[calc(100vh-4rem-16px)]">
    <h3 class="text-2xl font-semibold text-gray-800 mb-6">Yetkilendirme Yöneticisi</h3>

    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('message') }}</span>
        </div>
    @endif

    {{-- Kapsam Seçimi ve Filtreleme --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        <div>
            <label for="birim-select" class="block text-sm font-medium text-gray-700 mb-1">Birim Seçin</label>
            <select id="birim-select" wire:model.live="selectedBirimId" class="block w-full p-2 border border-gray-300 rounded-md shadow-sm">
                @foreach($birims as $birim)
                    <option value="{{ $birim->id }}">{{ $birim->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="bolum-select" class="block text-sm font-medium text-gray-700 mb-1">Bölüme Göre Filtrele</label>
            <select id="bolum-select" wire:model.live="selectedBolumId" class="block w-full p-2 border border-gray-300 rounded-md shadow-sm">
                <option value="">-- Tüm Kapsamlar --</option>
                @foreach($bolumsOfSelectedBirim as $bolum)
                    <option value="{{ $bolum->id }}">{{ $bolum->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- Kullanıcı Listesi --}}
    <div class="overflow-x-auto bg-gray-50 rounded-lg p-4">
        <div class="flex justify-between items-center mb-2">
            <h4 class="text-lg font-semibold text-gray-700">Yetkili Kullanıcılar</h4>
            <button wire:click="openNewRoleModal()" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700">
                Yeni Rol Ata
            </button>
        </div>

        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-100">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kullanıcı</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Atanmış Roller ve Kapsamları</th>
            </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
            @forelse($this->scopedUsers as $user)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap align-top">
                        <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                        <div class="text-xs text-gray-500">{{ $user->email }}</div>
                    </td>
                    <td class="px-6 py-4 align-top">
                        <div class="space-y-2">
                            @foreach($user->displayRoles as $roleInfo)
                                <div class="flex items-center justify-between">
                                    <div>
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                {{ $roleInfo['role_name'] }}
                                            </span>
                                        <span class="text-xs text-gray-600 ml-2">
                                                (Kapsam: {{ $roleInfo['scope_name'] }})
                                            </span>
                                    </div>
                                    <button
                                        wire:click="openRoleModal({{ $user->id }}, '{{ $roleInfo['scope_type'] === 'Birim' ? 'birim' : 'bolum' }}', {{ $roleInfo['scope_id'] }})"
                                        class="text-blue-600 hover:text-blue-900 text-xs font-medium"
                                    >
                                        Düzenle/Sil
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="2" class="px-6 py-4 text-center text-gray-500">Bu kriterlere uygun yetkili kullanıcı bulunamadı.</td>
                </tr>
            @endif
            </tbody>
        </table>
    </div>

    {{-- RoleModal'ı çağır. Kapsam bilgilerini event ile alacağı için prop geçmeye gerek yok. --}}
    <livewire:settings.role-modal />
</div>
