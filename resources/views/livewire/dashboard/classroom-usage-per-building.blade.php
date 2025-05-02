<div class="h-full flex items-center justify-center">
    @if($columnChartModel)
        {{-- Tam kapsayıcıyı doldursun --}}
        <div class="w-full h-full">
            <livewire:livewire-column-chart
                :key="$columnChartModel->reactiveKey()"
                :column-chart-model="$columnChartModel"
            />
        </div>
    @else
        <p class="text-center">Veri yok.</p>
    @endif

        <style>
            svg text {
                font-size: 8px !important;
            }
        </style>
</div>
