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
            let dataId = event.dataTransfer.getData("text");  // Ders ID
            let type = event.dataTransfer.getData("type");    // Veri türü (Ders mi, Classroom mı?)
            let name = event.dataTransfer.getData("name");    // Sürüklenen öğenin adı (Ders adı veya Classroom adı)
            let targetCell = event.target;                    // Bırakılan hücre
            let day = targetCell.dataset.day;                 // Gün verisi
            let hour = targetCell.dataset.hour;               // Saat verisi

            if (!targetCell.classList.contains("dropzone") || targetCell.querySelector(".draggable")) {
                return;
            }

            /*
            let newElement = document.createElement("div");
            newElement.classList.add("draggable");
            newElement.setAttribute("draggable", "true");
            newElement.setAttribute("ondragstart", "drag(event)");
            */
            if (type === "course") {
                let day = targetCell.dataset.day;   // Gün bilgisi
                let hour = targetCell.dataset.hour; // Saat bilgisi
                //newElement.innerHTML = `${name} <br> (${hour}:00, Gün ${day})`;

                window.dispatchEvent(new CustomEvent('addToSchedule', {
                    detail: { courseId: dataId, day: day, start_time: hour }
                }));

            } else if (type === "classroom") {
                window.dispatchEvent(new CustomEvent('addClassroomToSchedule', {
                    detail: { classroomId: dataId, day: day, start_time: hour }
                }));
            }
            targetCell.appendChild(newElement);
        }
    </script>
</div>
