<div class="p-2 w-full">
    <div class="col-md-4">
        <div class="form-group">
            <label class="block text-sm font-medium mb-1">Öğretim Görevlisi Ara</label>
            <div class="relative">
                <div class="relative">
                    <input
                        type="text"
                        wire:model.live.debounce.300ms="search"
                        wire:focus="$set('showSearchResults', true)"
                        placeholder="İsim, email ile arayın..."
                        class="w-full px-4 py-2 border  border-gray-700 bg-gray-800 text-white rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 pr-10"
                        autocomplete="off">

                    @if($selectedInstructor)
                        <button
                            wire:click="clearInstructorSelection"
                            class="absolute right-2 top-2 text-gray-400 hover:text-gray-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    @endif
                </div>
                @if($showSearchResults && count($searchResults) > 0)
                    <div class="absolute z-50 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg max-h-60 overflow-y-auto">
                        @foreach($searchResults as $instructor)
                            <div
                                wire:click="selectInstructor({{ $instructor->id }}, '{{ $instructor->name ?? $instructor->adi }}')"
                                class="px-4 py-3 hover:bg-gray-50 cursor-pointer border-b border-gray-100 last:border-b-0">
                                <div class="flex flex-col">
                                    <div class="font-medium text-gray-900">
                                        {{ $instructor->name ?? $instructor->adi }}
                                    </div>
                                    @if($instructor->email)
                                        <div class="text-sm text-gray-500">{{ $instructor->email }}</div>
                                    @endif
                                    @if($instructor->courses && $instructor->courses->isNotEmpty())
                                        <div class="text-xs text-gray-400 mt-1">
                                            @php
                                                $bolums = $instructor->courses->pluck('bolum.name')->unique()->filter();
                                            @endphp
                                            @if($bolums->isNotEmpty())
                                                {{ $bolums->implode(', ') }}
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                @if($showSearchResults && strlen($search) >= 2 && count($searchResults) === 0)
                    <div class="absolute z-50 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg">
                        <div class="px-4 py-3 text-gray-500 text-center">
                            Sonuç bulunamadı
                        </div>
                    </div>
                @endif
            </div>

            </div>
    </div>
    <div>
        <label class="block text-sm font-medium mb-1">Birim</label>
        <select wire:model.live="selectedBirim" class="w-full p-2 border border-gray-700 bg-gray-800 text-white rounded">
            <option value="">Birim Seçiniz</option>
            @foreach($birims as $birim)
                <option value="{{ $birim->id }}">{{ $birim->name }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium mb-1">Bölüm</label>
        <select wire:model="selectedBolum" class="w-full p-2 border border-gray-700 bg-gray-800 text-white rounded" @if(empty($bolums)) disabled @endif>
            <option value="">Bölüm Seçiniz</option>
            @foreach($bolums as $bolum)
                <option value="{{ $bolum['id'] }}">{{ $bolum['name'] }}</option>
            @endforeach
        </select>
    </div>



    <div class="pt-2">
        <button type="button"
                wire:click="$toggle('showAdvancedSearch')"
                class="flex items-center text-sm text-blue-400 hover:text-blue-300">
            <span>{{ $showAdvancedSearch ? 'Detaylı Aramayı Gizle' : 'Detaylı Arama' }}</span>
            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $showAdvancedSearch ? 'M19 9l-7 7-7-7' : 'M9 5l7 7-7 7' }}"></path>
            </svg>
        </button>
    </div>
    @if($showAdvancedSearch)
        <div>
            <label class="block text-sm font-medium mb-1">Günler</label>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                @foreach($days as $key => $day)
                    <label class="flex items-center space-x-2">
                        <input type="checkbox" wire:model="selectedDays" value="{{ $key }}" class="rounded border-gray-700 bg-gray-800 text-blue-600">
                        <span class="text-sm">{{ $day }}</span>
                    </label>
                @endforeach
            </div>
        </div>
        <div class="flex justify-center gap-4 align-middle">
            <div class="w-1/2">
                <label class="block text-sm mb-1">Başlangıç Saati</label>
                <input type="time" wire:model="startTime" class="w-full p-2 border border-gray-700 bg-gray-800 text-white rounded">
            </div>
            <div class="w-1/2">
                <label class="block text-sm mb-1">Bitiş Saati</label>
                <input type="time" wire:model="endTime" class="w-full p-2 border border-gray-700 bg-gray-800 text-white rounded">
            </div>
        </div>

    @endif
    <div class="flex p-4 justify-center space-x-8">
        <button wire:click="resetFilters" class="px-3 py-1 bg-gray-700 hover:bg-gray-600 text-white rounded text-sm">
            Temizle
        </button>
        <button wire:click="applyFilters" class="px-4 py-1 bg-blue-500 hover:bg-blue-800 text-white rounded text-sm">
            Filtrele
        </button>
    </div>
</div>
