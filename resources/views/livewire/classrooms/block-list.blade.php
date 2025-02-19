<div>
<div class="container">
    <div class="classrooms-section">
        @if ($selectedCampus && $selectedBuilding)
            <div class="flex justify-between">
                <div></div>
                <div>
                    <p class="building-title">{{ $selectedBuilding }}</p>
                </div>
                <div class="flex justify-between gap-2">
                    <button class="add-button">+</button>

                    <input type="text" placeholder="Ara..." class="search-input">
                    <svg class="search-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m2.85-5.65a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>

                </div>

            </div>
        @endif

        <div class="classrooms-container">
            @if ($selectedCampus && $selectedBuilding)
                @foreach ($campusesAndBuildings[$selectedCampus][$selectedBuilding] as $classroom)
                    <div class="classroom-item" draggable="true">
                        <p class="font-light" style="font-size: 8px">{{ $classroom['name'] }}</p>
                    </div>
                @endforeach
            @else
                <div>Kayıt Yok</div>
            @endif

        </div>

    </div>

    <div class="filter-section">
        @livewire('classrooms.dropdown-filter', ['campusesAndBuildings' => $campusesAndBuildings])
    </div>
</div>

<style>
    .container {
        display: flex;
        align-items: center;
        position: relative;
        max-width: none;
        padding: 20px;
    }

    .classrooms-section {
        flex: 1;
        white-space: nowrap;
        margin-right: 20px;
        display: flex;
        justify-content: center;
        text-align: center;
        flex-direction: column;
        overflow-x: auto;
        gap: 5px;
     }


    .classrooms-container {
        display: flex;
        gap: 15px;
        padding: 5px;
        background-color: #f0f0f0;
        border-radius: 8px;
        border: 1px solid #ddd;
        flex-wrap: wrap;
        overflow-x: auto;
        max-height: 200px;
        scroll-behavior: smooth;

    }

    .classroom-item {
        background-color: #fff;
        padding: 5px;
    }
    .building-title {
        font-size: 20px;
        font-weight: bold;
        margin-bottom: 10px;
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

    .filter-section .livewire-dropdown {
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
