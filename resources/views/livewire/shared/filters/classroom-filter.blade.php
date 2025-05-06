<div class="space-y-4 p-4">
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
    <!-- Filtre Butonları -->
    <div class="flex justify-between pt-4">
        <button wire:click="resetFilters" class="px-3 py-1 bg-gray-700 hover:bg-gray-600 text-white rounded text-sm">
            Temizle
        </button>
        <button wire:click="applyFilters" class="px-4 py-1 bg-blue-600 hover:bg-blue-500 text-white rounded text-sm">
            Filtrele
        </button>
    </div>
</div>
