<x-page-layout>
    <div class="container mt-4">
        <h3 class="text-center">Scheduled Edilmemiş Dersler</h3>

        @if ($pieChartModel)
            <!-- Pie Chart'ı render etmek için LivewireCharts direktifini kullanıyoruz -->
            <div style="width: 100%; height: 400px;">
                <livewire:livewire-pie-chart
                    key="{{ $pieChartModel->reactiveKey() }}"
                    :pie-chart-model="$pieChartModel"
                ></livewire:livewire-pie-chart>
            </div>
        @else
            <p class="text-center">Veri bulunamadı.</p>
        @endif
    </div>

</x-page-layout>
