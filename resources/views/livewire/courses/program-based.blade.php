<div class="course-section">
    <div class="table-header">
        <!-- Search ve Add Button -->
        <input type="text" placeholder="Ders arayın..." class="search-input">
        <button class="add-button">
            <i class="fa-solid fa-plus-circle fa-flip text-green-500 "></i>
        </button>
    </div>
    <div>
        <div class="courses-container">
            @foreach($this->courses as $courseClass)
                @php
                    $progress = $courseClass->duration > 0 ? ($courseClass->unscheduled_hours / $courseClass->duration) * 100 : 0;
                @endphp
                <div
                    wire:key="course-class-{{ $courseClass->id }}"
                    data-id="{{$courseClass->id}}"
                    data-constraints="{{ $courseClass?->instructor?->constraints }}"
                    wire:mouseenter.self.debounce.250.prevent ="$dispatch('showDetail', {
                                                                ' ':' ', //classroom ile eşit sayıda olsun diye
                                                               'Ders Adı':'{{ addslashes($courseClass->course->name) }}',
                                                               'Ders Kodu':'{{ addslashes($courseClass->course->code)}}',
                                                               'Kontenjan' : '{{addslashes($courseClass->quota)}} kişi',
                                                               'Süre' : '{{addslashes($courseClass->duration)}} saat',
                                                               'Şube' : '{{addslashes($courseClass->branch)}}',
                                                               'Hoca' : '{{addslashes($courseClass->instructorTitle)}} {{addslashes($courseClass->instructorName)}} {{addslashes($courseClass->instructorSurname)}}'
                                                               })"
                    class="course-item"
                    draggable="true"
                    ondragstart="drag(event, {{ $courseClass->id }})"
                    ondragend="dragEnd(event)"
                    ondblclick="Livewire.dispatch('open-course-modal', {courseId: '{{$courseClass->course->id }}', courseName: '{{$courseClass->course->name }}'})"
                    data-type="course">
                    @if($courseClass->instructor?->constraints?->first()?->id)
                        <div class="constraint-indicator" title="Bu hocanın zaman kısıtları var">
                            <i class="fa-solid fa-exclamation-triangle text-orange-500"></i>
                        </div>
                    @endif
                    <div class="course-details" >
                        <span class="course-code">{{str($courseClass->course->code)->words(3) }}</span>
                        <span class="course-duration">{{$courseClass->displayBranch }}</span>

                    </div>
                    <div class="course-details">
                        <span class="course-name">{{str($courseClass->course->displayName)->words(3) }}</span>
                        <span class="instructor-name">{{$courseClass->instructor?->shortName}}</span>
                    </div>

                    <div class="progress-container">
                        <div class="progress-bar" style="width: {{ $progress }}%"></div>
                    </div>
                </div>
            @endforeach
        </div>
        @if($showCourseModal ?? false)
            <livewire:schedule.course.schedule-chart :course-id="$selectedCourseId" :course-name="$selectedCourseName" />
        @endif
        <style>
            .table-header {
                display: flex;
                justify-content: space-between;
                gap: 10px;
                margin-bottom: 15px;
            }
            .constraint-indicator {
                position: absolute;
                top: 5px;
                right: 5px;
                font-size: 12px;
            }

            .search-input {
                padding: 8px 16px;
                border-radius: 5px;
                border: 1px solid #ccc;
                width: 250px;
                font-size: 14px;
                outline: none;
            }

            .courses-container {
                display: flex;
                flex-wrap: wrap;
                gap: 10px;
                margin-top: 15px;
            }
            .course-section {
                padding: 10px;
            }
            .course-item {
                background-color: #fff;
                padding: 12px;
                border-radius: 8px;
                border: 1px solid #ddd;
                width: calc(50% - 10px);
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
                cursor: grab;
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
                justify-content: space-evenly;
                gap: 10px;
                font-size: 10px;
            }

            .course-code {
                font-weight: bold;
                flex: 1;
            }
            .course-name {
                font-size: 8px;
            }
            .instructor-details{
                display: flex;
                justify-content: center;
            }
            .instructor-name {
                display: flex;
                flex-direction: column;
                font-size: 8px;
            }
            .progress-container {
                width: 100%;
                height: 8px;
                background-color: #e5e7eb;
                border-radius: 4px;
                overflow: hidden;
            }

            .progress-bar {
                height: 100%;
                background-color: #10b981;
                transition: width 0.4s ease-in-out;
            }
        </style>
</div>
</div>
