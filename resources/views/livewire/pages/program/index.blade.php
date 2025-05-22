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
        document.addEventListener('DOMContentLoaded', function (e) {
            Livewire.on('show-confirm', (event) => {
                const data = event[0];
                Swal.fire({
                    title: data.type === 'error' ? 'Hata!' : 'Başarılı!',
                    text: data.message,
                    icon: data.type,
                    confirmButtonText: 'Tamam',
                    timer: data.type === 'error' ? null : 2000,
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
</x-page-layout>
