<div class="h-full items-center justify-center">
    @if($columnChartModel)
        <div class="w-full h-full">
            <livewire:livewire-column-chart
                :key="$columnChartModel->reactiveKey()"
                :column-chart-model="$columnChartModel"
            />
        </div>
    @else
        <p class="text-center">Veri yok.</p>
    @endif
</div>
