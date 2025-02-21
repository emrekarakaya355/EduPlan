<div>

<div class="container">
    <div class="classrooms-section">
        @if ($selectedCampus && $selectedBuilding)
            <div class="flex justify-between">
                <div class="flex justify-between gap-2 items-center">


                    <button
                        wire:click="toggleForm"
                        class="p-2 bg-green-500 text-white rounded-md shadow-md hover:bg-green-600 ">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                        </svg>
                    </button>


                    <div class="relative">
                        <input type="text" placeholder="Ara..." class="search-input w-16 px-3 py-1 border rounded-md">
                    </div>
                </div>
                <div>
                    <p class="building-title">{{ $selectedBuilding }}</p>
                </div>
                <div></div>

            </div>
        @endif
        @if(false)
            <div>
                @foreach($classrooms as $campusName => $buildings)
                    <div class="campus-container">
                        @foreach($buildings as $buildingName => $classrooms)
                            <div class="building-container">
                                <h5 class="font-semibold my-1 ml-4 text-sm">{{ $buildingName }}</h5>
                                <div class="classrooms-container flex gap-2 flex-wrap">
                                    @foreach($classrooms as $classroom)
                                        <div class="classroom-item p-2 border rounded bg-white shadow"
                                             draggable="true"
                                             wire:mouseover.debounce="$dispatch('showDetail', { model: 'Classroom', id: {{ $classroom['id'] }} })"
                                             ondragstart="drag(event)"
                                             data-type="classroom">
                                            <p class="font-bold">{{ $classroom['name'] }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endforeach

            </div>
            @endif



        <div class="classrooms-container">
            @if(false)
                @foreach($classrooms as $classroom)
                    <div class="classroom-item" draggable="true"
                         wire:mouseover.debounce="$dispatch('showDetail', { model: 'Classroom', id: {{ $classroom['id'] }} })">
                        <p class="font-bold">{{ $classroom['name'] }}</p>
                    </div>
                @endforeach
            @endif
            @if ($selectedCampus && $selectedBuilding)
                @foreach ($campusesAndBuildings[$selectedCampus][$selectedBuilding] as $classroom)
                    <div class="classroom-item" draggable="true"
                         wire:mouseover.debounce="$dispatch('showDetail', { model: 'Classroom', id: {{ $classroom['id'] }} })">
                        <p class="font-bold">{{ $classroom['name'] }}</p>
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
    @if ($showCreateForm)
        <livewire:classrooms.create-classroom :selectedBuildingId="$selectedBuildingId"/>
    @endif
</div>

<style>
    .container {
        display: flex;
        align-items: center;
        padding: 10px;
    }

    .classrooms-section {
        flex: 1;
        margin-right: 20px;
        display: flex;
        flex-direction: column;
        gap: 5px;
     }


    .classrooms-container {
        display: flex;
        gap: 10px;
        padding: 5px;
        background-color: #f0f0f0;
        border-radius: 8px;
        border: 1px solid #ddd;
        overflow-y: auto;
        max-height: 150px;
        scroll-behavior: smooth;
        white-space: nowrap;
        flex-wrap: wrap;

    }
    .classroom-item {
        max-height: fit-content;
        max-width: fit-content;
        background-color: #fff;
        padding: 3px;
        border-radius: 8px;
        border: 1px solid #ddd;
        width: calc(50% - 10px); /* 2 kart yanyana */
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        cursor: move;
        transition: transform 0.3s ease;
        display: flex;
        flex-direction: column;
        gap: 5px;
    }
    .classroom-item:hover {
        background-color: #f4f4f4;
        transform: scale(1.05);
    }
    .classroom-item p{
        font-size: 10px;
        color: black;
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

    <script>
        function drag(event) {
            // Ders ID ve türünü taşıyoruz
            event.dataTransfer.setData("text", event.target.dataset.id);
            event.dataTransfer.setData("type", event.target.dataset.type);
            event.dataTransfer.setData("name", event.target.innerText.trim());
        }
    </script>
</div>
