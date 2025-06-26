<div class="course-section">
    <div class="table-header">
        <input type="text" placeholder="Ders arayın..." class="search-input">
        @if(false)
            <button class="add-button">
                <i class="fa-solid fa-plus-circle fa-flip text-green-500 "></i>
            </button>
        @endif
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
                    data-instructor-id="{{$courseClass->instructor_id}}"
                    data-constraints="{{ $courseClass?->instructor?->constraints }}"
                    wire:mouseenter.self.debounce.250.prevent ="$dispatch('showDetail', {
                                                                ' ':' ', //classroom ile eşit sayıda olsun diye
                                                               'Ders Adı':'{{ addslashes($courseClass->course->name) }}',
                                                               'Ders Kodu':'{{ addslashes($courseClass->course->code)}}',
                                                               'Kontenjan' : '{{addslashes($courseClass->quota)}} kişi',
                                                               'Süre' : '{{addslashes($courseClass->theoretical_duration) .'T + '. addslashes($courseClass->practical_duration) .'U = '. addslashes($courseClass->duration)}} saat',
                                                               'Şube' : '{{addslashes($courseClass->branch)}}',
                                                               'Hoca' : '{{addslashes($courseClass->instructorTitle)}} {{addslashes($courseClass->instructorName)}} {{addslashes($courseClass->instructorSurname)}}'
                                                               })"
                    class="course-item"
                    draggable="true"
                    ondragstart="drag(event, {{ $courseClass->id }})"
                    ondragend="dragEnd(event)"
                    ondblclick="Livewire.dispatch('open-instructor-modal', {instructorId: '{{$courseClass?->instructorId }}', instructorName: '{{$courseClass?->instructor?->name}}'})"
                    oncontextmenu="showContextMenu(event, {{ $courseClass->id }}, {{ $courseClass->instructorId ?? 'null' }}, '{{ addslashes($courseClass->course->name) }}', '{{ addslashes($courseClass->instructorName) }} {{ addslashes($courseClass->instructorSurname) }}')"
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
    </div>
    @if($showInstructorConstraintModal)
        <div class="relative">
         <livewire:settings.instructor-constraints
            :instructor-id="$selectedInstructorId"
            is-modal="true"
        />
        </div>
    @endif
    <!-- Context Menu -->
    <div id="contextMenu" class="context-menu" style="display: none;">
        <ul>
            <li onclick="openInstructorConstraints()">
                <i class="fa-solid fa-clock"></i>
                <span>Hoca Zaman Kısıtları</span>
            </li>
            <li onclick="editCourse()">
                <i class="fa-solid fa-edit"></i>
                <span>Dersi Düzenle</span>
            </li>
            <li onclick="viewCourseDetails()">
                <i class="fa-solid fa-info-circle"></i>
                <span>Ders Detayları</span>
            </li>
            <li onclick="duplicateCourse()">
                <i class="fa-solid fa-copy"></i>
                <span>Dersi Kopyala</span>
            </li>
            <li class="divider"></li>
            <li onclick="deleteCourse()" class="danger">
                <i class="fa-solid fa-trash"></i>
                <span>Dersi Sil</span>
            </li>
        </ul>
    </div>

    @if(false)
    <!-- Instructor Constraints Modal -->
    <div id="instructorConstraintsModal" class="constraints-modal" style="display: none;">
        <div class="modal-backdrop" onclick="closeInstructorConstraintsModal()"></div>
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="modalTitle">Eğitmen Zaman Kısıtlamaları</h3>
                <button onclick="closeInstructorConstraintsModal()" class="modal-close">
                    <i class="fa-solid fa-times"></i>
                </button>
            </div>
            <div id="constraintsContent" class="modal-body">
                <!-- Livewire component buraya yüklenecek -->
            </div>
        </div>
    </div >
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
            width: 100%;
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
            position: relative;
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
            position: relative;
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

        /* Context Menu Styles */
        .context-menu {
            position: fixed;
            background: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            z-index: 1000;
            min-width: 200px;
            overflow: hidden;
        }

        .context-menu ul {
            list-style: none;
            margin: 0;
            padding: 4px 0;
        }

        .context-menu li {
            padding: 10px 16px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 14px;
            transition: background-color 0.2s;
        }

        .context-menu li:hover {
            background-color: #f5f5f5;
        }

        .context-menu li.danger {
            color: #dc3545;
        }

        .context-menu li.danger:hover {
            background-color: #fee;
        }

        .context-menu li.divider {
            height: 1px;
            background-color: #eee;
            margin: 4px 0;
            padding: 0;
        }

        .context-menu li.divider:hover {
            background-color: #eee;
        }

        .context-menu i {
            width: 16px;
            text-align: center;
        }
    </style>

    <script>
        let contextMenuData = {
            courseId: null,
            instructorId: null,
            courseName: '',
            instructorName: ''
        };

        function showContextMenu(event, courseId, instructorId, courseName, instructorName) {
            event.preventDefault();

            contextMenuData = {
                courseId: courseId,
                instructorId: instructorId,
                courseName: courseName,
                instructorName: instructorName
            };
            const contextMenu = document.getElementById('contextMenu');

            // Menü pozisyonunu ayarla
            contextMenu.style.left = event.pageX + 'px';
            contextMenu.style.top = event.pageY + 'px';
            contextMenu.style.display = 'block';

            // Ekran sınırlarını kontrol et
            const rect = contextMenu.getBoundingClientRect();
            const windowWidth = window.innerWidth;
            const windowHeight = window.innerHeight;

            if (rect.right > windowWidth) {
                contextMenu.style.left = (event.pageX - rect.width) + 'px';
            }

            if (rect.bottom > windowHeight) {
                contextMenu.style.top = (event.pageY - rect.height) + 'px';
            }

            // Instructor yoksa constraint menüsünü devre dışı bırak
            const constraintItem = contextMenu.querySelector('li:first-child');
            if (!instructorId) {
                constraintItem.style.opacity = '0.5';
                constraintItem.style.pointerEvents = 'none';
                constraintItem.title = 'Bu dersin eğitmeni yok';
            } else {
                constraintItem.style.opacity = '1';
                constraintItem.style.pointerEvents = 'auto';
                constraintItem.title = '';
            }
        }

        function hideContextMenu() {
            const contextMenu = document.getElementById('contextMenu');
            contextMenu.style.display = 'none';
        }

        function openInstructorConstraints() {
            Livewire.dispatch('open-instructor-constraints-modal', {
                instructorId: contextMenuData.instructorId
            });
            /**
            if (!contextMenuData.instructorId) {
                alert('Bu dersin eğitmeni bulunmamaktadır.');
                hideContextMenu();
                return;
            }
            const modal = document.getElementById('instructorConstraintsModal');
            const modalTitle = document.getElementById('modalTitle');
            const content = document.getElementById('constraintsContent');

            modalTitle.textContent = `${contextMenuData.instructorName} - Zaman Kısıtlamaları`;

            modal.style.display = 'flex';
            */

            hideContextMenu();
        }



        function editCourse() {
            Livewire.dispatch('edit-course', {courseId: contextMenuData.courseId});
            hideContextMenu();
        }

        function viewCourseDetails() {
            Livewire.dispatch('view-course-details', {courseId: contextMenuData.courseId});
            hideContextMenu();
        }

        function duplicateCourse() {
            if (confirm(`"${contextMenuData.courseName}" dersini kopyalamak istediğinizden emin misiniz?`)) {
                Livewire.dispatch('duplicate-course', {courseId: contextMenuData.courseId});
            }
            hideContextMenu();
        }

        function deleteCourse() {
            // Dersi sil
            if (confirm(`"${contextMenuData.courseName}" dersini silmek istediğinizden emin misiniz? Bu işlem geri alınamaz.`)) {
                Livewire.dispatch('delete-course', {courseId: contextMenuData.courseId});
            }
            hideContextMenu();
        }

        document.addEventListener('click', function(event) {
            const contextMenu = document.getElementById('contextMenu');
            if (contextMenu && !contextMenu.contains(event.target)) {
                hideContextMenu();
            }
        });

        // Scroll sırasında menüyü kapat
        document.addEventListener('scroll', hideContextMenu);

        // Escape tuşu ile kapat
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                hideContextMenu();
                closeInstructorConstraintsModal();
            }
        });

        // Sayfa yüklendiğinde context menu'yu gizle
        document.addEventListener('DOMContentLoaded', function() {
            hideContextMenu();
        });
    </script>
</div>
