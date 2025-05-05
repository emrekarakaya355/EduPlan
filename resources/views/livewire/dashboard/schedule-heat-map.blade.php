<div class="bg-white rounded-lg shadow p-4">
    <h2 class="text-lg font-semibold mb-4">Derslik Saatlik Yoğunluk Haritası</h2>

    <div wire:ignore>
        <div id="heatmap-chart" style="height: 400px;"></div>
    </div>

    <script>
        document.addEventListener('livewire:initialized', () => {
            const chartData = @json($heatmapData);
            const categories = @json($categories);

            const options = {
                series: chartData,
                chart: {
                    type: 'heatmap',
                    height: 400,
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
                    title: {
                        text: '',
                        style: {
                            fontSize: '14px'
                        }
                    }
                },
                plotOptions: {
                    heatmap: {
                        shadeIntensity: 0.5,
                        radius: 0,
                        useFillColorAsStroke: false,
                        colorScale: {
                            ranges: [
                                { from: 0, to: 0, name: 'Boş', color: '#e5e7eb' },
                                { from: 1, to: 10, name: 'Az', color: '#93c5fd' },
                                { from: 11, to: 25, name: 'Orta', color: '#3b82f6' },
                                { from: 26, to: 40, name: 'Yoğun', color: '#1e40af' },
                                { from: 41, to: 999, name: 'Aşırı Yoğun', color: '#991b1b' }
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

            // Livewire veri güncellemeleri için
            Livewire.on('heatmapUpdated', (data) => {
                chart.updateSeries(data.heatmapData);
            });
        });
    </script>
</div>
