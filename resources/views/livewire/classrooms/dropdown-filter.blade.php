<div>
<div>
    <livewire:classrooms.classroom-bulk-import></livewire:classrooms.classroom-bulk-import>

    <div>
        <!-- Campus Seçimi -->
        <div class="campus-box">
            <select wire:model.live.debounce="selectedCampus" class="campus-select">
                <option value="">Seçiniz</option>
                @foreach ($campusesAndBuildings as $campusName => $buildings)
                    <option value="{{ $campusName }}" {{ $selectedCampus === $campusName ? 'selected' : '' }}>
                        {{ $campusName }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Building Seçimi -->
        <div class="buildings-container">
            <select wire:model.live.debounce="selectedBuilding" class="building-select">
                <option value="">Seçiniz</option>
                @if ($selectedCampus)
                    @foreach ($campusesAndBuildings[$selectedCampus] as $buildingName => $classrooms)
                        <option value="{{ $buildingName }}" {{ $selectedBuilding === $buildingName ? 'selected' : '' }}>
                            {{ $buildingName }}
                        </option>
                    @endforeach
                @endif
            </select>
        </div>
    </div>
</div>

<style>
    .campus-box,
    .buildings-container {
        margin-bottom: 15px;
    }

    .campus-select,
    .building-select {
        width: 250px;
        padding: 8px;
        font-size: 14px;
        border-radius: 5px;
        border: 1px solid #ccc;
        background-color: #f9f9f9;
    }

    .campus-select option,
    .building-select option {
        padding: 8px;
        font-size: 14px;
    }

    .campus-select:focus,
    .building-select:focus {
        border-color: #4caf50;
        background-color: #f1f1f1;
    }
</style>
</div>
