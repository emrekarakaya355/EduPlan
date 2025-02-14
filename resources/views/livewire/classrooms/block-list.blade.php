<div>
<div x-data="{ activeTab: 'birim', selectedCampus: null, selectedBuilding: null }" class="container">

    <!-- Birim / Bölüm Sekmeleri -->
    <div class="tabs">
        <button class="tab-button" :class="{'active': activeTab === 'birim'}" x-on:click="activeTab = 'birim';selectedCampus = null; selectedBuilding = null;">Birim</button>
        <button class="tab-button" :class="{'active': activeTab === 'bolum'}" x-on:click="activeTab = 'bolum';selectedCampus = null; selectedBuilding = null;">Bölüm</button>
    </div>

    <!-- Campus Kutusu (Sol Ortada) -->
    <div class="campus-box">
        <template x-if="activeTab === 'birim'">
            @foreach($this->classrooms as $campusName => $campus)
                <button class="campus-button"
                        :class="{'active': selectedCampus === '{{ $campusName }}'}"
                        x-on:click="selectedCampus = selectedCampus === '{{ $campusName }}' ? null : '{{ $campusName }}'; selectedBuilding = null;">
                    {{ $campusName }}
                </button>
            @endforeach
        </template>

        <template x-if="activeTab === 'bolum'">
            @foreach($this->bolumClassrooms as $campusName => $campus)
                <button class="campus-button"
                        :class="{'active': selectedCampus === '{{ $campusName }}'}"
                        x-on:click="selectedCampus = selectedCampus === '{{ $campusName }}' ? null : '{{ $campusName }}'; selectedBuilding = null;">
                    {{ $campusName }}
                </button>
            @endforeach
        </template>
    </div>

    <!-- Building Sekmeleri -->
    <div class="buildings-container" x-show="selectedCampus" x-transition>
        <template x-if="activeTab === 'birim'">
            @foreach($this->classrooms as $campusName => $campus)
                <div x-show="selectedCampus === '{{ $campusName }}'" class="buildings-list">
                    @foreach($campus as $buildingName => $classrooms)
                        <button class="building-tab"
                                :class="{'active': selectedBuilding === '{{ $buildingName }}'}"
                                x-on:click="selectedBuilding = selectedBuilding === '{{ $buildingName }}' ? null : '{{ $buildingName }}'">
                            {{ $buildingName }}
                        </button>
                    @endforeach
                </div>
            @endforeach
        </template>

        <template x-if="activeTab === 'bolum'">
            @foreach($this->bolumClassrooms as $campusName => $campus)
                <div x-show="selectedCampus === '{{ $campusName }}'" class="buildings-list">
                    @foreach($campus as $buildingName => $classrooms)
                        <button class="building-tab"
                                :class="{'active': selectedBuilding === '{{ $buildingName }}'}"
                                x-on:click="selectedBuilding = selectedBuilding === '{{ $buildingName }}' ? null : '{{ $buildingName }}'">
                            {{ $buildingName }}
                        </button>
                    @endforeach
                </div>
            @endforeach
        </template>
    </div>

    <!-- Seçili Building'e Ait Derslikler -->
    <div class="classrooms-container" x-show="selectedBuilding" x-transition>
        <template x-if="activeTab === 'birim'">
            @foreach($this->classrooms as $campusName => $campus)
                @foreach($campus as $buildingName => $classrooms)

                    <div x-show="selectedBuilding === '{{ $buildingName }}'" class="classrooms-list">
                        @foreach($classrooms as $classroom)
                            <div class="classroom-item">
                                <h5>{{ $classroom->name }}</h5>
                                <p>Kapasite: {{ $classroom->class_capacity }} kişi</p>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            @endforeach
        </template>

        <template x-if="activeTab === 'bolum'">
            @foreach($this->bolumClassrooms as $campusName => $campus)
                @foreach($campus as $buildingName => $classrooms)
                    <div x-show="selectedBuilding === '{{ $buildingName }}'" class="classrooms-list">
                        @foreach($classrooms as $classroom)
                            <div class="classroom-item">
                                <h5>{{ $classroom->name }}</h5>
                                <p>Kapasite: {{ $classroom->class_capacity }} kişi</p>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            @endforeach
        </template>
    </div>
</div>

<style>
    .container {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        position: relative;
    }

    /* Birim / Bölüm Sekmeleri */
    .tabs {
        display: flex;
        gap: 10px;
        margin-bottom: 10px;
    }

    .tab-button {
        background-color: #e0e0e0;
        border: 1px solid #ddd;
        padding: 10px;
        cursor: pointer;
    }

    .tab-button.active {
        background-color: #4caf50;
        color: white;
    }

    /* Campus Kutusu */
    .campus-box {
        position: relative;
        left: 10px;
        top: 50%;
        background: #f0f0f0;
        border-radius: 8px;
        border: 1px solid #ddd;
    }

    .campus-button {
        background: #e0e0e0;
        border: none;
        cursor: pointer;
        display: block;
        text-align: center;
        width: 200px;
        height: 20px;
        font-size: 8px;
        border-radius: 5px;
        margin-bottom: 5px;
    }

    .campus-button.active {
        background: #4caf50;
        color: white;
    }

    /* Building Sekmeleri */
    .buildings-container {
        width: 100%;
        display: flex;
        justify-content: flex-start;
    }

    .buildings-list {
        display: flex;
        gap: 10px;
        background: #f9f9f9;
    }

    .building-tab {
        background: #e0e0e0;
        padding: 10px;
        cursor: pointer;
        border-radius: 5px;
        border: none;
    }

    .building-tab.active {
        background: #4caf50;
        color: white;
    }

    /* Seçili Derslikler */
    .classrooms-container {
        width: 100%;
        display: flex;
        justify-content: center;
        margin-top: 10px;
        overflow-x: scroll;
    }

    .classrooms-list {
        display: flex;
        flex-wrap: nowrap;
        gap: 10px;
        background: #f0f0f0;
        padding: 10px;
        border-radius: 8px;
        border: 1px solid #ddd;
    }

    .classroom-item {
        background: #fff;
        padding: 10px;
        border-radius: 5px;
        border: 1px solid #ccc;
        text-align: center;
    }
</style>
</div>
