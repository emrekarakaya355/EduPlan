    <div class="classroom-container">
                @foreach ($classrooms as $classroom)
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
                        <p class="classroom-name">{{ $classroom['name'] }}</p>

                    </div>
                @endforeach

        <style>
            /* Container ve Kart Düzeni */
            .classroom-container {
                display: flex;
                flex-wrap: wrap;
                gap: 10px;
                overflow-y: hidden;
                padding: 10px;
            }

            .classroom-item {
                background-color: #fff;
                padding: 12px;
                border-radius: 8px;
                border: 1px solid #ddd;
                width: calc(100% - 10px);
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
                transition: transform 0.3s ease;
                display: flex;
                gap: 5px;
            }

            .classroom-item:hover {
                background-color: #f4f4f4;
                transform: scale(1.05);
            }


            .classroom-details {
                display: flex;
                flex-wrap: nowrap;
                gap: 10px;
                font-size: 10px;
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
                background-color: #c3e6cb;
            }
        </style>

    </div>

