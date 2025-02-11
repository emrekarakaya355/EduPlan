<div class="w-64 bg-gray-900 p-4 text-white">
    <label class="block mb-2">Birim</label>
    <select wire:model.live="unit" class="w-full p-2 border border-gray-700 bg-gray-800 text-white rounded">
        <option value="">Seçiniz</option>
        @foreach($units as $unit)
            <option value="{{ $unit->id }}">{{ $unit->name }}</option>
        @endforeach
    </select>

    <label class="block mt-4 mb-2">Bölüm</label>
    <select wire:model.live="department" class="w-full p-2 border border-gray-700 bg-gray-800 text-white rounded">
        <option value="">Seçiniz</option>
        @foreach($departments as $department)
            <option value="{{ $department->id }}">{{ $department->name }}</option>
        @endforeach
    </select>

    <label class="block mt-4 mb-2">Program</label>
    <select wire:model.live="program" class="w-full p-2 border border-gray-700 bg-gray-800 text-white rounded">
        <option value="">Seçiniz</option>
        @foreach($programs as $program)
            <option value="{{ $program->id }}">{{ $program->name }}</option>
        @endforeach
    </select>

    <div class="flex justify-between mt-4">
        <div class="w-1/2 mr-2">
            <label class="block mb-2">Yıl</label>
            <select wire:model.live="year" class="w-full p-2 border border-gray-700 bg-gray-800 text-white rounded">
                <option value="">Seçiniz</option>
                @foreach(range(date('Y'), date('Y') - 10) as $year)
                    <option value="{{ $year }}">{{ $year }}</option>
                @endforeach
            </select>
        </div>
        <div class="w-1/2 ml-2">
            <label class="block mb-2">Dönem</label>
            <select wire:model.live="semester" class="w-full p-2 border border-gray-700 bg-gray-800 text-white rounded">
                <option value="">Seçiniz</option>
                <option value="Fall">Güz</option>
                <option value="Spring">Bahar</option>
                <option value="Summer">Yaz</option>
            </select>
        </div>
    </div>

    <button wire:click="applyFilters" class="mt-4 w-full bg-red-600 hover:bg-red-700 text-white p-2 rounded transition">
        Filtrele
    </button>
</div>
