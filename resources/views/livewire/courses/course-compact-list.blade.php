<div>
    <div>
        <div>
            <div>
                <div class="table-header">
                    <!-- Search ve Add Button -->
                    <input type="text" placeholder="Ders arayın..." class="search-input">
                    <button class="add-button">+</button>
                </div>
                <div>

                </div>

                <div class="courses-container">
                    @foreach($courses as $courseClass)
                        <div
                            wire:key="course-class-{{ $courseClass->id }}" data-id="{{$courseClass->id}}"
                            wire:mouseover.debounce="$dispatch('showDetail', { model: 'Course_class', id: {{ $courseClass->id }} })"
                            class="course-item"
                            draggable="true"
                            ondragstart="drag(event)"
                            data-type="course">
                            <div class="course-details" >
                                <span class="course-name">{{str($courseClass->course->code)->words(3) }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
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
            width: calc(50% - 10px); /* 2 kart yanyana */
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

        .course-code, .course-branch, .course-quota, .course-duration {
            background-color: #f0f0f0;
            padding: 5px 8px;
            border-radius: 5px;
            font-size: 12px;
            color: #333;
        }

        .course-code {
            background-color: #e3f2fd;
        }

        .course-branch {
            background-color: #fce4ec;
        }

        .course-quota {
            background-color: #c8e6c9;
        }

        .course-duration {
            background-color: #fff3e0;
        }

        /* Arama input'unun ikonu */
        .search-input {
            padding-left: 10px;
            background-image: url('https://image.shutterstock.com/image-vector/search-icon-vector-260nw-123456789.jpg');
            background-position: left center;
            background-repeat: no-repeat;
        }
    </style>

    <script>
        function drag(event) {
            // Ders ID ve türünü taşıyoruz
            event.dataTransfer.setData("text", event.target.dataset.id);
            event.dataTransfer.setData("type", event.target.dataset.type);
            event.dataTransfer.setData("name", event.target.innerText.trim());
        }
    </script>
</div>
