<div class="container space-x-4">
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

            <!-- Building Seçimi -->
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
    <div class="classrooms-section" >
        <div></div>
        <div>
            <livewire:classrooms.block-list :classrooms="$filteredClassrooms" />
        </div>
    </div>


<style>
    .container {
        display: flex;
        align-items: end;
        padding: 10px;

    }
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
    .classrooms-section {
        flex: 1;
        margin-right: 20px;
        display: flex;
        flex-direction: column;
        gap: 5px;
    }


    .building-title {
        font-size: 12px;
        font-weight: bold;
        text-align: center;
        width: 100%;
    }
    .filter-section {
        position: sticky;
        top: 0;
        max-width: 300px;
        flex-shrink: 0;
        padding: 10px;
        border-radius: 8px;
        border: 1px solid #ddd;
        background-color: #f0f0f0;
    }

    .search-input {
        padding: 8px 32px 8px 12px;
        border: 1px solid #ccc;
        border-radius: 8px;
        font-size: 14px;
        width: 180px;
        outline: none;
    }

    .search-icon {
        position: relative;
        top: 50%;
        transform: translateY(-50%);
        width: 18px;
        height: 18px;
        color: #888;
    }
    .add-button {
        position: relative;
        width: 36px;
        height: 36px;
        background-color: #4caf50;
        color: black;
        font-size: 24px;
        font-weight: bold;
        border: none;
        border-radius: 50%;
        cursor: pointer;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        transition: background 0.3s ease-in-out;
    }

    .add-button:hover {
        background-color: #388e3c;
    }
</style>
</div>
