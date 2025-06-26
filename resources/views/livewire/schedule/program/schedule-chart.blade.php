<div>
    <div id="schedule-chart-content" class="p-2">
        <div class="flex justify-between" >
            <div class="flex space-x-4"  >
                <div>
                    <select
                        data-html2canvas-ignore="true"
                        id="courseSelect"
                        x-data
                        x-on:change="$dispatch('gradeUpdated',{grade : $event.target.value})"
                    >
                        @foreach($grades as $g)
                            <option value="{{ $g }}" {{ $grade == $g ? 'selected' : '' }}>
                                {{ \App\Models\Schedule::getGradeNameFromValue($g) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    @if(false)
                        <select id="sube" data-html2canvas-ignore="true">
                            <option value="A">A Şubesi</option>
                            <option value="B">B Şubesi</option>
                        </select>
                    @endif
                </div>
            </div>
            <div class="flex items-center flex-col">
                @isset($schedule)
                    <div>{{$schedule->year .' - '. Carbon\Carbon::createFromDate($schedule->year)->addYear()->year. ' ' . $schedule->semester  }}</div>

                    <div>{{$schedule->program?->name . ' ' }}</div>
                    <div>{{$schedule->gradeName .' Sınıf Ders Programı'}}</div>
                @endisset
            </div>
            <div class="flex" style="flex-direction: column">
            <div class="flex items-center space-x-2">
                <div data-html2canvas-ignore="true" class="no-print mb-4">
                    <button
                        wire:click="downloadPdf"
                        class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700"
                    >
                        <i class="fa-solid fa-file-image mr-2"></i> Pdf indir
                    </button>
                    @if(false)
                    <button
                        onclick="downloadScheduleAsPng()"
                        class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700"
                    >
                        <i class="fa-solid fa-file-image mr-2"></i> PNG Olarak Kaydet
                    </button>
                    @endif
                    <button onclick="openEditModal()"
                            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                        <i class="fa-solid fa-edit mr-2"></i> Düzelt ve Kaydet
                    </button>
                </div>
            </div >
                <div class="flex items-center space-x-2 justify-end">
                    @if(false)
                        <div>
                            <span data-html2canvas-ignore="true">Versiyon</span>
                            <select data-html2canvas-ignore="true">
                                <option>1</option>
                                <option>2</option>
                            </select>
                        </div>
                    @endif
                    <div>
                        <button data-html2canvas-ignore="true" class="rounded text-blue-500 text-2xl hover:bg-blue-100">
                            <i class="fa-solid fa-save"></i>
                        </button>

                    </div>
                    <div>
                        <button wire:click="toggleLock" data-html2canvas-ignore="true" class="rounded text-2xl hover:bg-gray-100 {{ $isLocked ? 'text-red-500' : 'text-green-500' }}">
                            <i class="fa-solid {{ $isLocked ? 'fa-lock' : 'fa-lock-open' }}"></i>
                        </button>
                    </div>
                    <div>
                        <button wire:click="openSettings" data-html2canvas-ignore="true" class="rounded text-green-500 text-2xl hover:bg-green-100">
                            <i class="fa-solid fa-gear fa-flip"></i>
                        </button>
                    </div>
                </div>

            </div>

        </div>
        @if($showInstructorModal ?? false)
            <livewire:schedule.instructor.schedule-chart :instructor-id="$selectedInstructorId" :instructor-name="$selectedInstructorName" :as-modal="true" />
        @endif
        @if($showSettings)
            <livewire:shared.schedule-settings-modal :scheduleId="$schedule?->id"/>
        @endif

        <div  class="grid grid-cols-{{ count($days) + 1 }} gap-1 p-4 schedule-grid ">

            <div class="p-2"></div>
            @foreach($days as $day)
                <div class="text-center font-bold p-2 bg-gray-800 text-white">{{ $day['label'] }}</div>
            @endforeach

            @foreach($scheduleData as $time => $days)
                <div class="text-center font-bold p-2 bg-gray-200">
                    {{ $time }}
                </div>

                @foreach(range(0,count($days)-1) as $day)
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
    @include('livewire.schedule.partials.edit-modal', ['scheduleData' => $scheduleData,'schedule'=>$schedule])
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
        function openEditModal() {
            const modal = document.getElementById('editModal');
            modal.classList.remove('hidden');
            modal.style.opacity = 0;
            setTimeout(() => modal.style.opacity = 1, 10);
        }

        function closeEditModal() {
            const modal = document.getElementById('editModal');
            modal.style.opacity = 0;
            setTimeout(() => modal.classList.add('hidden'), 300);
        }
        function saveAndExport() {
            const target = document.querySelector('#editModal > div');
            html2canvas(target, {
                scale: 2,
                backgroundColor: '#FFFFFF',
                ignoreElements: el => el.hasAttribute('data-html2canvas-ignore')
            }).then(canvas => {
                const link = document.createElement('a');
                link.download = 'duzenlenmis_ders_programi.png';
                link.href = canvas.toDataURL('image/png');
                link.click();
            });
        }
    </script>
    <style>
        [contenteditable="true"]:focus {
            background-color: #f0f9ff;
            outline: 2px solid #3b82f6 !important;
            overflow: visible;
            min-height: 50px;
        }

        /* Modal için geçiş efekti */
        #editModal {
            transition: opacity 0.3s ease;
        }
    </style>
</div>
