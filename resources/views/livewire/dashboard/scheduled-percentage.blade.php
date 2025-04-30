<div class="h-full flex items-center justify-center">
    @if($pieChartModel)
        <div class="w-full h-full">
            <livewire:livewire-pie-chart
                :key="$pieChartModel->reactiveKey()"
                :pie-chart-model="$pieChartModel"

            />
        </div>
    @else
        <p class="text-center">Veri bulunamadı.</p>
    @endif
</div>
