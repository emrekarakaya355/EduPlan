<div>
    <div class="courses-container">
        @foreach($this->courses as $courseClass)

            <div
                wire:key="course-class-{{ $courseClass->id }}" data-id="{{$courseClass->id}}"
                wire:mouseover="$dispatch('showDetail', { model: 'Course_class', id: {{ $courseClass->id }} })"
                class="course-item"
                draggable="true"
                ondragstart="drag(event, {{ $courseClass->id }})"
                data-type="course">
                <div class="course-details" >
                    <span class="course-name">{{str($courseClass->course->code)->words(3) }}</span>
                    <span class="course-duration">{{str($courseClass->unscheduledHours)->words(3) }}</span>
                </div>
                <div class="progress-container">
                    <div class="progress-bar" style="width: {{ $progress }}%"></div>
                </div>

            </div>
        @endforeach
    </div>

    <style>
        /* Başlık ve Butonlar */
        .table-header {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 15px;
        }

        .search-input {
            padding: 8px 16px;
            border-radius: 5px;
            border: 1px solid #ccc;
            width: 250px;
            font-size: 14px;
            outline: none;
        }

        /* Container ve Kart Düzeni */
        .courses-container {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            overflow-y: auto;
            margin-top: 15px;
        }

        .course-item {
            background-color: #fff;
            padding: 12px;
            border-radius: 8px;
            border: 1px solid #ddd;
            width: calc(33% - 10px); /* 3 kart yanyana */
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            cursor: move;
            transition: transform 0.3s ease;
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .course-item:hover {
            background-color: #f4f4f4;
            transform: scale(1.05);
        }

        .course-details {
            display: flex;
            flex-wrap: nowrap;
            gap: 10px;
            font-size: 10px;
        }

        .course-name {
            font-weight: bold;
            flex: 1;
        }
        .progress-container {
            width: 100%;
            height: 8px;
            background-color: #e5e7eb; /* Açık gri arkaplan */
            border-radius: 4px;
            overflow: hidden;
            margin-top: 5px;
        }

        .progress-bar {
            height: 100%;
            background-color: #10b981; /* Tailwind green-500 */
            transition: width 0.4s ease-in-out;
        }


    </style>

    <script>
        function drag(event) {
            event.dataTransfer.setData("text", event.target.dataset.id);
            event.dataTransfer.setData("type", event.target.dataset.type);
            event.dataTransfer.setData("name", event.target.innerText.trim());
        }
    </script>
</div>
