<x-page-layout>
    <livewire:schedule.program.schedule-chart />

    <div wire:ignore>
        <x-slot name="right">
            <livewire:courses.program-based />
        </x-slot>
        <x-slot name="top">
            <livewire:classrooms.classroom-listing-with-filters />
        </x-slot>
        <x-slot name="detay">
            <livewire:shared.dynamic-detail />
        </x-slot>
    </div>

    <script>
        let currentDragConstraints = null;

        document.addEventListener('DOMContentLoaded', function (e) {
            Livewire.on('show-confirm', (event) => {
                const data = event[0];
                Swal.fire({
                    title: data.type === 'error' ? 'Hata!' : 'Başarılı!',
                    html: data.message,
                    icon: data.type,
                    confirmButtonText: 'Tamam',
                    timer: data.type === 'error' ? 2000 : 2000,
                    timerProgressBar: true,
                    toast: data.type !== 'error',
                    position: data.type === 'error' ? 'center' : 'top-end',
                    showConfirmButton: data.type === 'error'
                });
            });

            Livewire.on('ask-confirmation', (event) => {
                const data = event[0];
                Swal.fire({
                    title: 'Emin misiniz?',
                    text: data.message,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Evet, ekle!',
                    cancelButtonText: 'İptal',
                    background: '#1a1a2e',
                    color: '#ffffff'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Livewire.dispatch('forceAddToSchedule', {
                            classId: data.classId,
                            day: data.day,
                            start_time: data.start_time
                        });
                    }
                });
            });
        });

        function drag(event) {
            event.dataTransfer.setData("text", event.target.dataset.id);
            event.dataTransfer.setData("type", event.target.dataset.type);
            event.dataTransfer.setData("name", event.target.innerText.trim());

            document.body.classList.add('dragging');

            if (event.target.dataset.constraints) {
                currentDragConstraints = JSON.parse(event.target.dataset.constraints);
                event.dataTransfer.setData("constraints", event.target.dataset.constraints);
                markForbiddenAreas();
            } else {
                currentDragConstraints = null;
            }

        }
        function markForbiddenAreas() {
            if (!currentDragConstraints || currentDragConstraints.length === 0) {
                return;
            }

            const dropzones = document.querySelectorAll('.dropzone');
            dropzones.forEach(dropzone => {
                const day = dropzone.dataset.day;
                const hour = dropzone.dataset.hour;

                if (isConstraintViolated(currentDragConstraints, day, hour)) {
                    dropzone.classList.add('drop-forbidden');
                    dropzone.setAttribute('data-forbidden', 'true');
                }
            });
        }

        function clearForbiddenAreas() {
            const forbiddenAreas = document.querySelectorAll('.drop-forbidden');
            forbiddenAreas.forEach(area => {
                area.classList.remove('drop-forbidden');
                area.removeAttribute('data-forbidden');
                area.style.cursor = '';
            });
        }

        function allowDrop(event) {
            event.preventDefault();

            let targetCell = event.target;

            if (targetCell.hasAttribute('data-forbidden')) {
                event.dataTransfer.dropEffect = "none";
                return false;
            }

            event.dataTransfer.dropEffect = "move";
            return true;
        }

        function dragEnd(event) {
            currentDragConstraints = null;
            clearForbiddenAreas();
            document.body.classList.remove('dragging');
        }
        function isConstraintViolated(constraints, day, hour) {

            if (!constraints || constraints.length === 0) {
                return false;
            }
            for (let constraint of constraints) {
                if (constraint.day_of_week == day) {
                    if (!constraint.start_time && !constraint.end_time) {
                        return true;
                    }
                    else if (constraint.start_time && constraint.end_time) {
                        if (hour >= constraint.start_time && hour <= constraint.end_time) {
                            return true;
                        }
                    }
                    else if (constraint.start_time && constraint.start_time === hour) {
                        return true;
                    }
                }
            }

            return false;
        }
        function drop(event) {
            event.preventDefault();
            let type = event.dataTransfer.getData("type");
            let dataId = event.dataTransfer.getData("text");
            let targetCell = event.target;
            let day = targetCell.dataset.day;
            let hour = targetCell.dataset.hour;
            let schedule = targetCell.dataset.schedule;
            let classId = targetCell.dataset.id;
            let externalId = targetCell.dataset.external;

            if (!targetCell.classList.contains("dropzone") || targetCell.querySelector(".draggable")) {
                return;
            }

            if (targetCell.hasAttribute('data-forbidden')) {
                alert('Bu hoca bu zaman diliminde ders veremez!');
                return;
            }

            if (type === "course") {
                window.dispatchEvent(new CustomEvent('addToSchedule', {
                    detail: { classId: dataId, day: day, start_time: hour, scheduleId:schedule }
                }));
            } else if (type === "classroom") {
                window.dispatchEvent(new CustomEvent('addClassroomToSchedule', {
                    detail: { classroomId: dataId, day: day, start_time: hour, classId:classId, externalId:externalId }
                }));
            }
        }
    </script>
    <style>
        .drop-forbidden {
            background-color: #fee2e2 !important;
            border: 2px solid #dc2626 !important;
            position: relative;
            cursor: not-allowed !important;
            opacity: 0.7;
        }

        .drop-forbidden::before {
            content: '🚫';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 20px;
            z-index: 10;
            pointer-events: none;
        }

        .drop-forbidden::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: repeating-linear-gradient(
                45deg,
                transparent,
                transparent 10px,
                rgba(220, 38, 38, 0.1) 10px,
                rgba(220, 38, 38, 0.1) 20px
            );
            pointer-events: none;
        }

        /* Drag sırasında diğer alanları biraz soldur */
        .dropzone:not(.drop-forbidden) {
            opacity: 1;
            transition: opacity 0.2s ease;
        }

        /* Drag yapılırken genel efekt */
        body.dragging .dropzone:not(.drop-forbidden) {
            background-color: #f0f9ff;
            border-color: #0ea5e9;
        }

        body.dragging .drop-forbidden {
            animation: shake 0.5s ease-in-out infinite;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-2px); }
            75% { transform: translateX(2px); }
        }
    </style>
</x-page-layout>
