<div class="w-full h-full">
    <div class="p-4 ">
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
                <div id="classroom-usage-chart"></div>
            </div>

    </div>
    @if($showClassroomModal ?? false)
        <livewire:schedule.classroom.schedule-chart
            :classroom-id="$selectedClassroomId"
            :classroom-name="$selectedClassroomName"
            :as-modal="true"
        />
    @endif
    @script
    <script>
            let chart;
            function initChart(chartData) {
                const numberOfClassrooms = chartData.labels.length;
                const minHeight = 350;
                const heightPerClassroom = 30;
                const dynamicHeight = Math.max(minHeight, numberOfClassrooms * heightPerClassroom);

                const options = {
                    series: [{
                        name: 'Kullanım Oranı',
                        data: chartData.data || []
                    }],
                    labels: chartData.labels,
                    chart: {
                        type: 'bar',
                        width : '100%',
                        height: dynamicHeight,
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

                                $wire.dispatch('classroomShow', {
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
                            horizontal: true,
                            distributed: true,
                            borderRadius: 4,
                            dataLabels: {
                                position: 'top'
                            },
                            barHeight : '50%'
                        },
                    },
                    colors: chartData.colors || ['#3b82f6'],
                    dataLabels: {
                        enabled: true,
                        formatter: function(val) {
                            return val + '%';
                        },
                        offsetx: -20,
                        style: {
                            fontSize: '12px',
                            colors: ["#304758"]
                        }
                    },
                    stroke : {
                      width:1,
                        colors:["#fff " ]
                    },

                    xaxis: {
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
                            },
                            style: {
                                fontSize : '12px'
                            }
                        }
                    },
                    tooltip: {
                        y: {
                            formatter: function(val) {
                                return val + '%';
                            }
                        }
                    },
                    grid: {
                        borderColor: '#e0e0e0',
                        strokeDashArray: 1,

                    },
                    legend:{
                        show:false
                    }
                };
                if (chart) {
                    chart.destroy();
                }
                chart = new ApexCharts(document.querySelector("#classroom-usage-chart"), options);
                chart.render();
            }
            const componentData = $wire.chartData;
            if (componentData && componentData.labels && componentData.labels.length > 0) {
                initChart(componentData);
            }
            $wire.on('classroomChartDataUpdated', (data) => {
                initChart(data.chartData);
            });
    </script>
    @endscript
</div>
