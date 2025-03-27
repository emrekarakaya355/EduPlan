<div class="p-2">
    <div class="flex justify-between">

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
        <div class="flex items-center flex-col">
            @isset($schedule)
                <div>{{$schedule['year'] .' - '. Carbon\Carbon::createFromDate($schedule['year'])->addYear()->year. ' ' . $schedule['semester']  }}</div>

                <div>{{$schedule['program']['name'] . ' ' }}</div>
                <div>{{$schedule['grade'] .'. Sınıf Ders Programı'}}</div>
            @endisset
        </div>
       <div class="flex">
           <div>
               <span>Versiyon</span>
               <select>
                   <option>1</option>
                   <option>2</option>
               </select>
           </div>
           <div>
               <button  class="px-4 py-2 rounded text-green-500">
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

        @foreach($calendarData as $time => $days)
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
                                 data-day="{{ $day + 1 }}" data-hour="{{ $time }}" ondragstart="drag(event)"
                                 ondragover="event.preventDefault()" ondrop="drop(event)">
                                <button wire:click="$dispatch('removeFromSchedule', { hour: '{{ $time }}', day: '{{ $day + 1 }}', courseId: '{{ $course['id'] ?? '' }}' })"
                                        class="absolute right-0 top-0 text-red-500 " style="margin-top: -5px">
                                    <i class="fa-solid fa-square-xmark fa-fade"></i>
                                </button>
                                <div class="text-sm name" style="pointer-events: none">{{ $course['class_code'] }}</div>
                                <div class="name" style="pointer-events: none; font-size: xx-small">{{ $course['class_name'] }}</div>
                                <div class="font-bold" style="pointer-events: none;font-size: xx-small"> {{ $course['teacher_name'] }}</div>
                                <div style="pointer-events: none; font-size: xx-small">{{ $course['classrom_name'] ?? '(Derslik Sonra Belirtilecek)' }}</div>
                                <div style="pointer-events: none; font-size: xx-small">{{ $course['building_name'] ?? '' }}</div>
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

