<div class="w-full h-full">
    <h2 class="text-lg font-semibold mb-4 text-center">Derslik Saatlik Yoğunluk Haritası</h2>

    <div id="heatmap-chart" class="w-full h-full"></div>

        @script
        <script>
            const chartData = $wire.heatmapData;
            const categories = $wire.categories;

            const options = {
                series: chartData,
                chart: {
                    type: 'heatmap',
                    height: 'auto',
                    width: '100%',
                    toolbar: {
                        show: true,
                        tools: {
                            download: true,
                            selection: true,
                            zoom: true,
                            zoomin: true,
                            zoomout: true,
                            pan: true,
                            reset: true
                        }
                    }
                },
                dataLabels: {
                    enabled: false
                },
                colors: ["#e5e7eb", "#93c5fd", "#3b82f6", "#1e40af"],
                xaxis: {
                    type: 'category',
                    categories: categories,
                    labels: {
                        style: {
                            fontSize: '10px'
                        }
                    },
                    title: {
                        text: 'Saatler',
                        style: {
                            fontSize: '12px'
                        }
                    }
                },
                yaxis: {
                    reversed : true,
                    title: {
                        text: '',
                        style: {
                            fontSize: '14px'
                        }
                    },
                },
                plotOptions: {
                    heatmap: {
                        shadeIntensity: 0.5,
                        radius: 0,
                        useFillColorAsStroke: false,
                        colorScale: {
                            ranges: [
                                { from: 0, to: 0, name: 'Boş', color: '#e5e7eb' },
                                { from: 1, to: 24, name: 'Az', color: '#93c5fd' },
                                { from: 25, to: 49, name: 'Orta', color: '#3b82f6' },
                                { from: 50, to: 99, name: 'Yoğun', color: '#1e40af' },
                                { from: 100, to: 999, name: 'Aşırı Yoğun', color: '#991b1b' }
                            ]
                        }
                    }
                },
                tooltip: {
                    y: {
                        formatter: function(val) {
                            return val + " ders";
                        }
                    }
                },
                legend: {
                    position: 'bottom',
                    horizontalAlign: 'center'
                }
            };

            const chart = new ApexCharts(document.querySelector("#heatmap-chart"), options);
            chart.render();
            Livewire.on('heatmapUpdated', (data) => {
                chart.updateSeries(data.heatmapData);
            });
    </script>
    @endscript
</div>
