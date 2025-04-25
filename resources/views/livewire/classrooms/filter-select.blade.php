<div>
    <div class="mb-4">
        <label for="campus" class="block font-semibold">Kampüs Seçin</label>
        <select wire:model.live="selectedCampus" id="campus" class="border rounded p-2 w-full">
            <option value="">-- Kampüs seçin --</option>
            @foreach($campuses as $campus)
                <option value="{{ $campus->id }}">{{ $campus->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="mb-4">
        <label for="building" class="block font-semibold">Bina Seçin</label>
        <select wire:model.live="selectedBuilding"  id="building" class="border rounded p-2 w-full">
            <option value="">-- Bina seçin --</option>
            @foreach($buildings as $building)
                <option value="{{ $building->id }}" {{ $building->id == $selectedBuilding ? 'selected' : '' }}>
                    {{ $building->name }}</option>
            @endforeach
        </select>
    </div>
</div>
