<div class="container mx-auto p-6 bg-white shadow-lg rounded-lg">
    <h2 class="text-3xl font-bold mb-6 text-gray-800">Yetkilendirme Ayarları</h2>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        {{-- Sol Panel: Birimler ve Altındaki Bölümler Listesi --}}
        <div class="md:col-span-1 bg-gray-50 p-4 rounded-lg shadow-sm max-h-[70vh] overflow-y-auto sticky top-0">
            <h3 class="text-xl font-semibold mb-4 text-gray-700">Yapısal Birimler</h3>
            <ul class="space-y-2">
                @forelse($birims as $birim)
                    <li>
                        <div
                            wire:click="selectBirim({{ $birim->id }})"
                            class="p-3 rounded-md cursor-pointer hover:bg-blue-100 transition duration-150 ease-in-out
                                {{ $selectedEntityId === $birim->id && $selectedEntityType === 'birim' ? 'bg-blue-200 text-blue-800 font-medium' : 'bg-white text-gray-700' }}"
                        >
                            <span class="font-bold">{{ $birim->name }}</span>
                            @if($birim->manager)
                                <p class="text-xs text-gray-500 mt-1">Sorumlu: {{ $birim->manager->adi }}</p>
                            @else
                                <p class="text-xs text-gray-500 mt-1">Sorumlu: Atanmamış</p>
                            @endif
                        </div>

                        {{-- Bölümleri sadece ilgili birim seçili ise göster --}}
                        @if($selectedEntityType === 'birim' && $selectedEntityId === $birim->id)
                            <ul class="ml-4 mt-2 space-y-1 border-l-2 border-gray-200 pl-4">
                                @forelse($this->bolumsForSelectedBirim as $bolum)
                                    <li
                                        wire:click="selectBolum({{ $bolum->id }})"
                                        class="p-2 rounded-md cursor-pointer hover:bg-green-100 transition duration-150 ease-in-out
                                            {{ $selectedEntityId === $bolum->id && $selectedEntityType === 'bolum' ? 'bg-green-200 text-green-800 font-medium' : 'bg-white text-gray-700' }}"
                                    >
                                        {{ $bolum->name }}
                                        @if($bolum->manager)
                                            <p class="text-xs text-gray-500 mt-1">Sorumlu: {{ $bolum->manager->adi }}</p>
                                        @else
                                            <p class="text-xs text-gray-500 mt-1">Sorumlu: Atanmamış</p>
                                        @endif
                                    </li>
                                @empty
                                    <li class="text-gray-500 text-sm">Bu birime bağlı bölüm bulunamadı.</li>
                                @endforelse
                            </ul>
                        @endif
                    </li>
                @empty
                    <li class="text-gray-500">Hiçbir birim bulunamadı.</li>
                @endforelse
            </ul>
        </div>

        {{-- Sağ Panel: Seçili Birim/Bölüm Detayları ve Yetkilileri --}}
        <div class="md:col-span-2 bg-white p-6 rounded-lg shadow-md border border-gray-200">
            @if($currentEntity)
                <h3 class="text-2xl font-bold mb-4 text-gray-800">
                    {{ $currentEntity->name }} Yetkilileri
                </h3>

                <p class="text-gray-600 mb-6">
                    Bu {{ $selectedEntityType === 'birim' ? 'birime' : 'bölüme' }} atanmış yetkili kullanıcılar ve sorumlulukları.
                </p>

                @if($this->entityAuthorizations->isNotEmpty())
                    <ul class="space-y-4">
                        @foreach($this->entityAuthorizations as $auth)
                            <li class="bg-gray-100 p-4 rounded-md shadow-sm border border-gray-200 flex items-center justify-between">
                                <div>
                                    <span class="font-semibold text-gray-800 text-lg">{{ $auth->user_adi }}</span>
                                    <span class="ml-3 text-sm text-blue-600 font-medium">({{ $auth->type }})</span>
                                    <p class="text-gray-600 text-sm">{{ $auth->detail }}</p>
                                </div>
                                {{-- Yönetim butonları (Ekle, Düzenle, Sil) --}}
                                <div>
                                    <button class="text-blue-500 hover:text-blue-700 text-sm font-semibold mr-2">Düzenle</button>
                                    <button class="text-red-500 hover:text-red-700 text-sm font-semibold">Sil</button>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-gray-500 p-4 bg-gray-50 rounded-md">
                        {{ $currentEntity->name }} için henüz bir yetkili atanmamış.
                    </p>
                @endif

                <div class="mt-8 pt-6 border-t border-gray-200">
                    <button class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md shadow-md transition duration-150 ease-in-out">
                        Yeni Yetkili Ekle
                    </button>
                </div>
            @else
                <p class="text-gray-500 text-lg">Seçilen birim veya bölüm bulunamadı.</p>
            @endif
        </div>
    </div>
</div>
