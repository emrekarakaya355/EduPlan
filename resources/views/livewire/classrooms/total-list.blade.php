    <div class="classroom-container">
                @foreach ($classrooms as $classroom)
                    @php
                        $usagePercent = min(100, ($classroom->total_usage_duration / 50) * 100);
                            if ($usagePercent < 50) {
                                $progressColor = '#38bdf8';
                            } elseif ($usagePercent < 80) {
                                $progressColor = '#facc15';
                            } else {
                                $progressColor = '#f87171';
                            }

                    @endphp
                    <div
                        wire:key="classroom-class-{{ $classroom['id'] }}"
                        data-id="{{$classroom['id']}}"
                        class="classroom-item {{ $classroom['id'] == $selectedClassroomId ? 'selected' : '' }}"
                        data-type="classroom"
                        wire:click="selectedClassroom({{ $classroom['id'] }})"
                        wire:mouseenter.debounce.250="$dispatch('showDetail', {  'Derslik Adı' : '{{addslashes( $classroom['name'])}}',
                                                                     'Fakülte' : '{{ addslashes($classroom['building']['campus']['name']) }}',
                                                                     'Bina' : '{{ addslashes($classroom['building']['name']) }}',
                                                                     'Sınıf Türü' : '{{ addslashes($classroom['type'])}}',
                                                                    'Ders Kapasitesi' : '{{ addslashes($classroom['class_capacity'] )}} kişi',
                                                                    'Sınav Kapasitesi' : '{{ addslashes($classroom['exam_capacity']) }} kişi',
                                                                     })">
                        <div class="classroom-content">
                            <p class="classroom-name">{{ $classroom['name'] }}</p>
                        </div>

                        <div class="circular-progress"
                             style="--progress: {{ $usagePercent }}%; --progress-color: {{ $progressColor }}">
                            <span >{{ $classroom->total_usage_duration }}</span>
                        </div>

                    </div>

                @endforeach

        <style>
            /* Container ve Kart Düzeni */
            .classroom-container {
                display: flex;
                flex-wrap: wrap;
                gap: 10px;
                padding: 10px;
            }

            .classroom-item {
                background-color: #fff;
                padding: 12px;
                border-radius: 8px;
                border: 1px solid #ddd;
                width: calc(100% - 10px);
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
                position: relative;
                transition: transform 0.3s ease;
                display: flex;
                gap: 5px;
            }

            .classroom-item:hover {
                background-color: #f4f4f4;
                transform: scale(1.05);
            }

            .classroom-name {
                font-weight: bold;
                flex: 1;
                font-size: 10px;
            }
            .classroom-item.selected {
                background-color: #2589d5;
                border-color: #2589d5;
                transform: scale(1.05);
            }

            .classroom-item.selected:hover {
                background-color: #c8d0dc;
            }

            .classroom-content {
                display: flex;
                align-items: center;
                height: 100%;
             }

            .classroom-name {
                font-weight: bold;
                font-size: 16px;
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
            }

            .circular-progress {
                --size: 30px;
                --thickness: 5px;
                --progress-color: #38bdf8;
                width: var(--size);
                height: var(--size);
                border-radius: 50%;
                background: conic-gradient(var(--progress-color) calc(var(--progress)), #e5e7eb 0);
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 12px;
                font-weight: bold;
                color: #333;
                position: absolute;
                top: 10px;
                right: 10px;
                box-sizing: border-box;
                transition: background 0.5s ease;
            }

            .circular-progress::before {
                content: "";
                position: absolute;
                width: calc(var(--size) - var(--thickness)*2);
                height: calc(var(--size) - var(--thickness)*2);
                background: #fff;
                border-radius: 50%;
                z-index: 1;
            }
            .circular-progress span {
                z-index: 2;
                font-size: 8px;
                position: relative;
            }


        </style>

    </div>

