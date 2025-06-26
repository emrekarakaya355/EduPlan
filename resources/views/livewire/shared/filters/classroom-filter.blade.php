<div class="p-2 w-full ">
    <div>
        <label class="block text-sm font-medium mb-1">Kampüs</label>
        <select wire:model.live="selectedCampus" class="w-full p-2 border border-gray-700 bg-gray-800 text-white rounded">
            <option value="">Kampüs Seçiniz</option>
            @foreach($campuses as $campus)
                <option value="{{ $campus['id'] }}">{{ $campus['name'] }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium mb-1">Bina</label>
        <select wire:model="selectedBuilding" class="w-full p-2 border border-gray-700 bg-gray-800 text-white rounded" @if(empty($buildings)) disabled @endif>
            <option value="">Bina Seçiniz</option>
            @foreach($buildings as $building)
                <option value="{{ $building['id'] }}">{{ $building['name'] }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block text-sm font-medium mb-1">Sınıf Türü</label>
        <select wire:model="selectedClassroomType" class="w-full p-2 border border-gray-700 bg-gray-800 text-white rounded">
            <option value="">Sınıf Türü Seçiniz</option>
            @foreach($classroomTypes as $type)
                <option value="{{ $type }}">{{ $type }}</option>
            @endforeach
        </select>
    </div>
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

    <div class="flex justify-center gap-4 align-middle">
        <div class="w-1/2">
            <label class="block text-sm mb-1">Ders Kontenjan</label>
            <input type="number" wire:model="minCapacity" class="w-full p-2 border border-gray-700 bg-gray-800 text-white rounded" placeholder="Minimum kontenjan" min="1">

        </div>
        <div class="w-1/2">
            <label class="block text-sm mb-1">Ders Kontenjan</label>
            <input type="number" wire:model="maxCapacity" class="w-full p-2 border border-gray-700 bg-gray-800 text-white rounded" placeholder="Maximum kontenjan" min="1">
        </div>
    </div>

    <div class="flex justify-center gap-4 align-middle">
        <div class="w-1/2">
            <label class="block text-sm mb-1">Sınav Kontenjan</label>
            <input type="number" wire:model="minExamCapacity" class="w-full p-2 border border-gray-700 bg-gray-800 text-white rounded" placeholder="Minimum Sınav kontenjan" min="1">

        </div>
        <div class="w-1/2">
            <label class="block text-sm  mb-1">Sınav Kontenjan</label>
            <input type="number" wire:model="maxExamCapacity" class="w-full p-2 border border-gray-700 bg-gray-800 text-white rounded" placeholder="Maximum Sınav kontenjan" min="1">
        </div>
    </div>
    <div>
        <label class="block text-sm font-medium mb-1">Aktiflik</label>
        <select wire:model="isActive" class="w-full p-2 border border-gray-700 bg-gray-800 text-white rounded">
            <option value="true">Aktif</option>
            <option value="false">Pasif</option>

        </select>
    </div>
    <div>
        <label class="block text-sm font-medium mb-1">Müsaitlik</label>
        <select wire:model="showAvailable" class="w-full p-2 border border-gray-700 bg-gray-800 text-white rounded">
            <option value="">Tümü</option>
            <option value="true">Dolu</option>
            <option value="false">Boş</option>

        </select>
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
