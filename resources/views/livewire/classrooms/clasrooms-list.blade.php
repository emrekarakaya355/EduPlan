<div>
    <div class="filter-section">
        <div>
            <div class="campus-box">
                <select wire:model.live="selectedCampus" class="campus-select">
                    <option value="">Seçiniz</option>
                    @foreach ($classrooms as $campusName => $buildings)
                        <option value="{{ $campusName }}" {{ $selectedCampus === $campusName ? 'selected' : '' }}>
                            {{ $campusName }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="buildings-container">
                <select wire:model.live="selectedBuilding" class="building-select">
                    <option value="">Seçiniz</option>
                    @if ($selectedCampus && isset($classrooms[$selectedCampus]))
                        @foreach ($classrooms[$selectedCampus] as $buildingName => $classrooms)
                            <option value="{{ $buildingName }}" {{ $selectedBuilding === $buildingName ? 'selected' : '' }}>
                                {{ $buildingName }}
                            </option>
                        @endforeach
                    @endif
                </select>
            </div>
        </div>
    </div>

</div>
