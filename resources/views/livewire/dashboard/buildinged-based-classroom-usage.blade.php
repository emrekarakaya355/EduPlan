<div class="h-full flex-1 items-center justify-center">
    <div>
        <label for="building" class="font-semibold">Bina Seç:</label>
        <select wire:model.live="selectedBuildingId" id="building" class="border rounded px-2 py-1">
            <option value="">-- Seçiniz --</option>
            @foreach($buildings as $building)
                <option value="{{ $building->id }}">{{ $building->name }}</option>
            @endforeach
        </select>
    </div>
    @if($columnChartModel)
        <div class="w-full h-full">
        <livewire:livewire-column-chart
            :key="$columnChartModel->reactiveKey()"
            :column-chart-model="$columnChartModel"
        />
     </div>
    @else
        <p class="text-gray-500">Lütfen bir bina seçiniz.</p>
    @endif
    <style>
        svg text {
            font-size: 8px !important;
        }
    </style>
</div>
