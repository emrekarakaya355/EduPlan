<div class="w-full h-full">
    <div class="p-4 bg-white rounded-lg shadow-md">
        <h2 class="text-lg font-semibold mb-4">Bina Bazlı Sınıf Kullanım Oranları</h2>

        <div class="mb-4">
            <label for="building-select" class="block text-sm font-medium text-gray-700 mb-1">Bina Seçin</label>
            <select
                id="building-select"
                wire:model.live="selectedBuildingId"
                class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
            >
                <option value="">Bina Seçin</option>
                @foreach($buildings as $building)
                    <option value="{{ $building->id }}">{{ $building->name }}</option>
                @endforeach
            </select>
        </div>

            <div wire:ignore>
                <div id="classroom-usage-chart" style="height: 350px;"></div>
            </div>

    </div>
    @if($showClassroomModal ?? false)
        <livewire:schedule.classroom.schedule-chart
            :classroom-id="$selectedClassroomId"
            :classroom-name="$selectedClassroomName"
            :as-modal="true"
        />
    @endif
    <script>
        document.addEventListener('livewire:initialized', () => {
            let chart;
            function initChart(chartData) {
                const options = {

                    series: [{
                        name: 'Kullanım Oranı',
                        data: chartData.data || []
                    }],
                    chart: {
                        type: 'bar',
                        height: 350,
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
                        },
                        events: {
                            dataPointSelection : function(event, chartContext, config) {
                                const selectedClassroomId = chartData.classroomIds[config.dataPointIndex];
                                console.log(selectedClassroomId);

                                const selectedClassroomName = chartData.labels[config.dataPointIndex];
                                console.log(selectedClassroomName);

                                @this.dispatch('classroomShow', {
                                    classroomId: selectedClassroomId,
                                    classroomName: selectedClassroomName
                                });
                            }
                        },
                    },
                    title: {
                        text: `${chartData.title}`+`\n`+` birimine ait derslik kullanım oranları`,
                        align: 'center',
                        style: {
                            fontSize: '8px',
                            fontWeight: 'bold',
                            color: '#374151'
                        }
                    },
                    plotOptions: {
                        bar: {
                            horizontal: false,
                            columnWidth: '20%',
                            borderRadius: 4,
                            dataLabels: {
                                position: 'top'
                            }
                        },
                    },
                    colors: chartData.colors || ['#3b82f6'],
                    dataLabels: {
                        enabled: true,
                        formatter: function(val) {
                            return val + '%';
                        },
                        offsetY: -20,
                        style: {
                            fontSize: '10px',
                            colors: ["#304758"]
                        }
                    },
                    xaxis: {
                        categories: chartData.labels || [],
                        position: 'bottom',
                        labels: {
                            rotate: -45,
                            rotateAlways: false,
                            style: {
                                fontSize: '8px'
                            }
                        },
                        axisBorder: {
                            show: false
                        },
                        axisTicks: {
                            show: false
                        }
                    },
                    yaxis: {
                        min: 0,
                        max: 100,
                        title: {
                            text: 'Kullanım Oranı (%)',
                            style: {
                                fontSize: '14px'
                            }
                        },
                        labels: {
                            formatter: function(val) {
                                return val.toFixed(0) + '%';
                            }
                        }
                    },
                    tooltip: {
                        y: {
                            formatter: function(val) {
                                return val + '% kullanım oranı';
                            }
                        }
                    },
                    grid: {
                        borderColor: '#e0e0e0',
                        strokeDashArray: 4,
                        yaxis: {
                            lines: {
                                show: true
                            }
                        }
                    }
                };

                if (chart) {
                    chart.destroy();
                }

                chart = new ApexCharts(document.querySelector("#classroom-usage-chart"), options);
                chart.render();
            }

            const componentData = @this.chartData;
            if (componentData && componentData.labels && componentData.labels.length > 0) {
                initChart(componentData);
            }
            @this.on('classroomChartDataUpdated', (data) => {
                initChart(data.chartData);
            });
        });
    </script>
</div>
