<div>
            <div wire:ignore>
                <div id="building-usage-chart"></div>
            </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('livewire:initialized', function () {
            const options = @this.chartOptions;
            const series = @this.chartSeries;
            console.log(options);
            // Make sure to properly parse the formatter functions from strings
            options.dataLabels.formatter = new Function('val', 'return val + "%"');
            options.yaxis.labels.formatter = new Function('val', 'return val + "%"');
            options.tooltip.y.formatter = new Function('val', 'return val + "%"');

            const chart = new ApexCharts(
                document.querySelector("#building-usage-chart"),
                {
                    ...options,
                    series: series
                }
            );

            chart.render();

            // Listen for Livewire updates and refresh chart
            Livewire.on('chartDataUpdated', (chartData) => {
                chart.updateOptions(chartData.options);
                chart.updateSeries(chartData.series);
            });
        });
    </script>
@endpush
