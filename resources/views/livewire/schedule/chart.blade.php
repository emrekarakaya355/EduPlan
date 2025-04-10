<div class="p-2">
    <div class="flex justify-between">
        <div class="flex space-x-4">
        <div>
            <select id="courseSelect" x-data x-on:change="$dispatch('gradeUpdated',{grade : $event.target.value})">
                <option value="0">Hazırlık</option>
                <option value="1" selected>1. Sınıf</option>
                <option value="2">2. Sınıf</option>
                <option value="3">3. Sınıf</option>
                <option value="4">4. Sınıf</option>
                <option value="5">5. Sınıf</option>
                <option value="6">6. Sınıf</option>
                <option value="7">7. Sınıf</option>
                <option value="8">8. Sınıf</option>
                <option value="9">9. Sınıf</option>
            </select>
        </div>
            <div>
                <select id="sube" >
                    <option value="A">A Şubesi</option>
                    <option value="B">B Şubesi</option>
                </select>
            </div>
        </div>
        <div class="flex items-center flex-col">
            @isset($schedule)
                <div>{{$schedule->year .' - '. Carbon\Carbon::createFromDate($schedule->year)->addYear()->year. ' ' . $schedule->semester  }}</div>

                <div>{{$schedule->program?->name . ' ' }}</div>
                <div>{{$schedule->grade .'. Sınıf Ders Programı'}}</div>
                <div>{{$schedule->id}}</div>
            @endisset
        </div>
        <div class="flex items-center space-x-2">
           <div>
               <span>Versiyon</span>
               <select>
                   <option>1</option>
                   <option>2</option>
               </select>
           </div>
           <div>
               <button class="rounded text-blue-500 text-2xl hover:bg-blue-100">
                    <i class="fa-solid fa-save"></i>
               </button>
           </div>
           <div>
               <button class="rounded text-green-500 text-2xl hover:bg-green-100">
                   <i class="fa-solid fa-gear fa-flip"></i>
               </button>
           </div>
       </div>
    </div>
    <div class="grid grid-cols-7 gap-1 p-4" style="grid-template-columns: repeat(7, minmax(0, 1fr));">
        <div class="p-2"></div>

        <div class="text-center font-bold p-2 bg-gray-800 text-white">Pazartesi</div>
        <div class="text-center font-bold p-2 bg-gray-800 text-white">Salı</div>
        <div class="text-center font-bold p-2 bg-gray-800 text-white">Çarşamba</div>
        <div class="text-center font-bold p-2 bg-gray-800 text-white">Perşembe</div>
        <div class="text-center font-bold p-2 bg-gray-800 text-white">Cuma</div>
        <div class="text-center font-bold p-2 bg-gray-800 text-white">Cumartesi</div>

        @foreach($scheduleData as $time => $days)
            <div class="text-center font-bold p-2 bg-gray-700 text-white">
                {{ $time }}
            </div>

            @foreach(range(0, 5) as $day)
                @if (isset($days[$day]) && is_array($days[$day]))
                    <div class="flex border overflow-hidden text-center align-middle">
                        @foreach($days[$day] as $course)
                            <div wire:key="{{ $time }}-{{ $course['id'] }}"
                                 class="border-l dropzone relative flex-1"
                                 draggable="true"
                                 data-id="{{ $course['id'] ?? '' }}"
                                 data-type="course"
                                 data-schedule ="{{ $schedule->id }}"
                                 data-day="{{ $day + 1 }}" data-hour="{{ $time }}" ondragstart="drag(event)"
                                 ondragover="event.preventDefault()" ondrop="drop(event)">
                                <button wire:click="$dispatch('removeFromSchedule', { hour: '{{ $time }}', day: '{{ $day + 1 }}', courseId: '{{ $course['id'] ?? '' }}' })"
                                        class="absolute right-0 top-0 text-red-500 " style="margin-top: -5px">
                                    <i class="fa-solid fa-square-xmark fa-fade"></i>
                                </button>
                                <div class="text-sm name" style="pointer-events: none">{{ $course['class_code'] }}</div>
                                <div class="name" style="pointer-events: none; font-size: xx-small">{{ $course['class_name'] }}</div>
                                <div class="font-bold" style="pointer-events: none;font-size: xx-small"> {{ $course['instructor_name'] }}</div>
                                <div style="pointer-events: none; font-size: xx-small">{{ $course['classrom_name'] ?? '(Derslik Sonra Belirtilecek)' }}</div>
                                <div style="pointer-events: none; font-size: xx-small">{{ $course['building_name'] ?? '' }}</div>
                            </div>
                        @endforeach
                    </div>
                @else

                    <div class="border bg-white dropzone"
                         data-day="{{ $day + 1  }}" data-hour="{{ $time }}"
                         data-schedule ="{{ $schedule?->id }}"
                         ondragover="event.preventDefault()" ondrop="drop(event)">
                    </div>
                @endif
            @endforeach
        @endforeach
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
                const data = event[0]; // Livewire 3'te veriler event[0] içinde

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
                            courseId: data.courseId,
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
            if (!targetCell.classList.contains("dropzone") || targetCell.querySelector(".draggable")) {
                return;
            }
            if (type === "course") {

                window.dispatchEvent(new CustomEvent('addToSchedule', {
                    detail: { courseId: dataId, day: day, start_time: hour, scheduleId:schedule }
                }));
            } else if (type === "classroom") {
                window.dispatchEvent(new CustomEvent('addClassroomToSchedule', {
                    detail: { classroomId: dataId, day: day, start_time: hour }
                }));
            }
        }
    </script>
</div>

