<div>

    <livewire:schedule.chart :schedule="$schedule" :calendar-data="$scheduleData"/>

    <x-slot name="right">
        <livewire:courses.index />
    </x-slot>
    <x-slot name="top">
        <livewire:classrooms.index />
    </x-slot>
    <x-slot name="detay">
        <livewire:dynamic-detail />
    </x-slot>
    <script>

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
            if (!targetCell.classList.contains("dropzone") || targetCell.querySelector(".draggable")) {
                return;
            }
            if (type === "course") {

                window.dispatchEvent(new CustomEvent('addToSchedule', {
                    detail: { courseId: dataId, day: day, start_time: hour }
                }));
            } else if (type === "classroom") {
                window.dispatchEvent(new CustomEvent('addClassroomToSchedule', {
                    detail: { classroomId: dataId, day: day, start_time: hour }
                }));
            }
        }
    </script>
</div>
