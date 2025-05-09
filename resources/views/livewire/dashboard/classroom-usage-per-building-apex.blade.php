<div>
<div >
            <div wire:ignore>
                <div id="building-usage-chart"></div>
            </div>
</div>

@script
    <script>
        const options = $wire.chartOptions;
        const series = $wire.chartSeries;
        console.log(options);
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

        Livewire.on('chartDataUpdated', (chartData) => {
            chart.updateOptions(chartData.options);
            chart.updateSeries(chartData.series);
        });
    </script>
    @endscript
</div>
