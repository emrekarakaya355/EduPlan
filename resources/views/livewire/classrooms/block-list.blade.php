<div class="classrooms-container">
    @if (!empty($classrooms))
        @foreach ($classrooms as $classroom)
            <div class="classroom-item" draggable="true"
                 wire:mouseover.debounce="$dispatch('showDetail', { model: 'Classroom', id: {{ $classroom['id'] }} })">
                <p class="font-bold">{{ $classroom['name'] }}</p>
            </div>
        @endforeach
    @else
        <div>Kayıt Yok</div>
    @endif
    <style>
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
            cursor: move;
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
