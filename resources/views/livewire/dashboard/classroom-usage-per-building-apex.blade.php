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
        options.dataLabels.formatter = new Function('val', 'return val + "%"');
        options.yaxis.labels.formatter = new Function('val', 'return val + "%"');
        options.tooltip.y.formatter = new Function('val', 'return val + "%"');

        options.chart.events = {
            dataPointSelection: function(event, chartContext, config) {
                try {
                    console.log('Chart clicked!', config);

                    const clickedBuildingIndex = config.dataPointIndex;
                    const buildingData = $wire.buildingUsage[clickedBuildingIndex];

                    if (buildingData && buildingData.id) {
                         $wire.dispatch('buildingSelectedFromApexChart', { buildingId: buildingData.id });
                    } else {
                        console.error('Building data bulunamadı:', buildingData);
                    }

                } catch (error) {
                    console.error('Chart click event hatası:', error);
                }
            }
        };
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
