<div >
    <div class="flex justify-between">

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
        {{$schedule?->id}}
        <button class="bg-green-500 w-10 h-10 rounded-full text-xl shadow-md hover:bg-green-600">
                +
        </button>

    </div>
    <div class="grid grid-cols-8 gap-1 p-4" style="grid-template-columns: repeat(8, minmax(0, 1fr));">
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


        @foreach($calendarData as $time => $days)
            <!-- Saat sütunu -->
            <div class="text-center font-bold p-2 bg-gray-700 text-white">
                {{ $time }}
            </div>

            @foreach(range(0, 6) as $day)
                @if (isset($days[$day]) && is_array($days[$day]))
                    <div class="flex border overflow-hidden text-center align-middle">
                        @foreach($days[$day] as $course)
                            <div wire:key="{{ $time }}-{{ $course['id'] }}"
                                 class="border-l dropzone relative flex-1"
                                 draggable="true"
                                 data-id="{{ $course['id'] ?? '' }}"
                                 data-type="course"
                                 data-day="{{ $day + 1 }}" data-hour="{{ $time }}" ondragstart="drag(event)"
                                 ondragover="event.preventDefault()" ondrop="drop(event)">
                                <button wire:click="$dispatch('removeFromSchedule', { hour: '{{ $time }}', day: '{{ $day + 1 }}', courseId: '{{ $course['id'] ?? '' }}' })"
                                        class="absolute right-0 top-0 ml-2 text-red-500 ">x</button>
                                <div class="text-sm name" style="pointer-events: none">{{ $course['class_code'] }}</div>
                                <div class="name" style="pointer-events: none; font-size: xx-small">{{ $course['class_name'] }}</div>
                                <div class="font-bold" style="pointer-events: none;font-size: xx-small"> {{ $course['teacher_name'] }}</div>
                                <div style="pointer-events: none; font-size: xx-small">{{ $course['classrom_name'] ?? '(Derslik Sonra Belirtilecek)' }}</div>
                            </div>
                        @endforeach
                    </div>
                @else

                    <div class="border bg-white dropzone"
                         data-day="{{ $day + 1  }}" data-hour="{{ $time }}"
                         ondragover="event.preventDefault()" ondrop="drop(event)">
                    </div>
                @endif
            @endforeach
        @endforeach
    </div>


</div>

