<div>

    <livewire:schedule.chart />

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

            if (type === "course") {
               window.dispatchEvent(new CustomEvent('addToSchedule', {
                    detail: { courseId: dataId }
                }));

            } else if (type === "classroom") {
                alert(1);
            }



        }


    </script>
</div>
