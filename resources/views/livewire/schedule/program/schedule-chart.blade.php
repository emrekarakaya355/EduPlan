<div>
    <div id="schedule-chart-content" class="p-2">
        <div class="flex justify-between" >
            <div class="flex space-x-4"  >
                <div>
                    <select data-html2canvas-ignore="true" id="courseSelect" x-data x-on:change="$dispatch('gradeUpdated',{grade : $event.target.value})">
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
                    <select id="sube" data-html2canvas-ignore="true">
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
                @endisset
            </div>
            <div class="flex items-center space-x-2"  >
                <div>
                    <span data-html2canvas-ignore="true">Versiyon</span>
                    <select data-html2canvas-ignore="true">
                        <option>1</option>
                        <option>2</option>
                    </select>
                </div>
                <div>
                    <button data-html2canvas-ignore="true" class="rounded text-blue-500 text-2xl hover:bg-blue-100">
                        <i class="fa-solid fa-save"></i>
                    </button>
                </div>
                <div>
                    <button data-html2canvas-ignore="true" class="rounded text-green-500 text-2xl hover:bg-green-100">
                        <i class="fa-solid fa-gear fa-flip"></i>
                    </button>
                </div>
                <div data-html2canvas-ignore="true" class="no-print mb-4">
                    <button
                        onclick="downloadScheduleAsPng()"
                        class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700"
                    >
                        <i class="fa-solid fa-file-image mr-2"></i> PNG Olarak Kaydet
                    </button>
                </div>
            </div>
        </div>
        @if($showInstructorModal ?? false)
            <livewire:schedule.instructor.schedule-chart :instructor-id="$selectedInstructorId" :instructor-name="$selectedInstructorName" :as-modal="true" />
        @endif

        <div  class="grid grid-cols-6 gap-1 p-4 schedule-grid">

            <div class="p-2"></div>
            @foreach(['Pazartesi', 'Salı', 'Çarşamba', 'Perşembe', 'Cuma'] as $day)
                <div class="text-center font-bold p-2 bg-gray-800 text-white">{{ $day }}</div>
            @endforeach

            @foreach($scheduleData as $time => $days)
                <div class="text-center font-bold p-2 bg-gray-200">
                    {{ $time }}
                </div>
                @foreach(range(0,4) as $day)
                    @include('livewire.schedule.partials.day-cell', [
                        'day' => $day,
                        'time' => $time,
                        'classes' => $days[$day] ?? null,
                        'scheduleId' => $schedule?->id ?? -1
                    ])
                @endforeach
            @endforeach
        </div>

    </div>
    <script>
        function downloadScheduleAsPng() {
            const target = document.getElementById('schedule-chart-content');
            html2canvas(target, { scale: 2 }).then(canvas => {
                const link = document.createElement('a');
                link.download = 'ders_programi.png';
                link.href = canvas.toDataURL();
                link.click();
            });
        }
    </script>
</div>
