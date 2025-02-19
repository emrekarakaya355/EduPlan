<div>
<div class="flex justify-between">

    <select id="courseSelect" wire:model.live="grade">
        <option value="0">Hazırlık</option>
        <option value="1">1. Sınıf</option>
        <option value="2">2. Sınıf</option>
        <option value="3">3. Sınıf</option>
        <option value="4">4. Sınıf</option>
        <option value="5">5. Sınıf</option>
        <option value="6">6. Sınıf</option>
        <option value="7">7. Sınıf</option>
        <option value="8">8. Sınıf</option>
        <option value="9">9. Sınıf</option>
    </select>
    <button class="bg-green-500  w-10 h-10 rounded-full text-xl shadow-md hover:bg-green-600">
            +
    </button>

</div>
<div class="grid grid-cols-8 gap-1 p-4">
    <!-- Sol üst boş kutu -->
    <div class="p-2"></div>

    <!-- Günler -->
    <div class="text-center font-bold p-2 bg-gray-800 text-white">Pazartesi</div>
    <div class="text-center font-bold p-2 bg-gray-800 text-white">Salı</div>
    <div class="text-center font-bold p-2 bg-gray-800 text-white">Çarşamba</div>
    <div class="text-center font-bold p-2 bg-gray-800 text-white">Perşembe</div>
    <div class="text-center font-bold p-2 bg-gray-800 text-white">Cuma</div>
    <div class="text-center font-bold p-2 bg-gray-800 text-white">Cumartesi</div>
    <div class="text-center font-bold p-2 bg-gray-800 text-white">Pazar</div>

    <!-- Saatler ve çizelge -->
    @foreach(range(8, 20) as $hour)
        <div class="text-center font-bold p-2 bg-gray-700 text-white">{{ $hour }}:00</div>
        @foreach(range(1, 7) as $day)
            <div draggable="true" class="border p-4 min-h-[50px] bg-gray-100 dropzone"  ondragstart="drag(event)" data-day="{{ $day }}" data-hour="{{ $hour }}" ondragover="event.preventDefault()"
                 ondrop="drop(event)"></div>
        @endforeach
    @endforeach
</div>


 <x-slot name="right">
     <livewire:courses.course-compact-list :courses="$this->courseClasses"/>
 </x-slot>

<x-slot name="top">
     <livewire:classrooms.block-list />

</x-slot>
</div>

<script>
    function drag(event) {
        event.dataTransfer.setData("text", event.target.dataset.id);
        event.dataTransfer.setData("html", event.target.outerHTML);
    }

    function drop(event) {
        event.preventDefault();

        let dataId = event.dataTransfer.getData("text");  // Ders ID
        let html = event.dataTransfer.getData("html");    // Ders HTML içeriği
        let targetCell = event.target;                    // Bırakılan hücre
        let day = targetCell.dataset.day;                 // Gün verisi
        let hour = targetCell.dataset.hour;               // Saat verisi

        // Eğer zaten bir ders varsa üzerine eklemeyi engelle
        if (targetCell.classList.contains("dropzone") && !targetCell.querySelector(".draggable")) {
            targetCell.innerHTML = html;
            let newElement = targetCell.querySelector(".draggable");

            // Tekrar draggable hale getir
            newElement.setAttribute("draggable", "true");
            newElement.setAttribute("ondragstart", "drag(event)");

            // Gün ve saat bilgisini dersin içine yaz
            let courseName = newElement.innerText.trim();
            targetCell.innerHTML = `<div class="p-2 bg-blue-500 text-white rounded shadow draggable"
                                 draggable="true" ondragstart="drag(event)">
                                    ${courseName} <br> (${hour}:00, Gün ${day})
                                </div>`;
        }
    }


</script>
