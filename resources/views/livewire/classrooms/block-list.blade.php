<div class="classrooms-container">

    @if (!empty($classrooms))
        @foreach ($classrooms as $classroom)
            @php
                // totalUsageDuration'a göre yüzdelik hesapla
                $progress = $classroom['total'] > 0 ? ($classroom['total'] / 50) * 100 : 0;
            @endphp
            <div
                wire:key="classroom-class-{{ $classroom['id'] }}"
                data-id="{{$classroom['id']}}"
                class="classroom-item"
                draggable="true"
                data-type="classroom"
                ondragstart="drag(event, {{ $classroom['id'] }})"
                ondblclick="Livewire.dispatch('open-classroom-modal', {classroomId: '{{$classroom['id'] }}', classroomName: '{{addslashes( $classroom['name'])}}'})"

                wire:mouseenter.debounce.250="$dispatch('showDetail', {  'Derslik Adı' : '{{addslashes( $classroom['name'])}}',
                                                                     'Fakülte' : '{{ addslashes($classroom['building']['campus']['name']) }}',
                                                                     'Bina' : '{{ addslashes($classroom['building']['name']) }}',
                                                                     'Sınıf Türü' : '{{ addslashes($classroom['type'])}}',
                                                                    'Ders Kapasitesi' : '{{ addslashes($classroom['class_capacity'] )}} kişi',
                                                                    'Sınav Kapasitesi' : '{{ addslashes($classroom['exam_capacity']) }} kişi',
                                                                     })">
                <p class="font-bold">{{ $classroom['name'] }}</p>
                <div class="progress-container">
                    <div class="progress-bar" style="width: {{ $progress }}%"></div>
                </div>
            </div>

        @endforeach
    @else
        <div>Kayıt Yok</div>
    @endif

    <style>
        .progress-percentage {
            font-size: 12px;
            font-weight: bold;
        }
        .progress-bar {
            height: 100%;
            background-color: #10b981;
            transition: width 0.4s ease-in-out;
        }
        .classrooms-container {
            display: flex;
            flex:1;
            gap: 10px;
            padding: 5px;
            background-color: #f0f0f0;
            border-radius: 8px;
            border: 1px solid #ddd;
            overflow-x: auto;
            max-height: 100px;
            scroll-behavior: smooth;
            flex-wrap: wrap;
            vertical-align: center;
            align-items: center;
        }
        .classroom-item {
            max-height: fit-content;
            max-width: fit-content;
            background-color: #fff;
            padding: 3px;
            border-radius: 8px;
            border: 1px solid #ddd;
            width: calc(50% - 10px); /* 2 kart yanyana */
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            cursor: grab;
            transition: transform 0.3s ease;
            display: flex;
            flex-direction: column;
            gap: 5px;
            white-space: nowrap;

        }
        .classroom-item:hover {
            background-color: #f4f4f4;
            transform: scale(1.05);
        }
        .classroom-item p{
            font-size: 10px;
            color: black;
        }


    </style>
</div>
