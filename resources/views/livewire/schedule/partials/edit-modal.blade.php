<div
    id="editModal"
    class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden"
    x-data
    x-on:keydown.escape.window="closeEditModal()"
    x-on:click.self="closeEditModal()"
>
    <div
        class="bg-white rounded-lg shadow-lg w-11/12 max-h-[95vh] p-6 overflow-auto"
        x-on:click.stop
    >
        <!-- Modal Header -->
        <div class="flex justify-between items-center mb-4">
            <div>
                <h3 data-html2canvas-ignore="true" class="text-xl font-bold">Ders Programını Düzenle</h3>
            </div>
            <div class="flex items-center flex-col">
                @isset($schedule)
                    <div>{{$schedule->year .' - '. Carbon\Carbon::createFromDate($schedule->year)->addYear()->year. ' ' . $schedule->semester  }}</div>

                    <div>{{$schedule->program?->name . ' ' }}</div>
                    <div>{{$schedule->grade .'. Sınıf Ders Programı'}}</div>
                @endisset
            </div>
            <div>
                <button
                    data-html2canvas-ignore="true"
                    @click="closeEditModal()"
                    class="text-gray-600 hover:text-gray-900 text-2xl font-bold leading-none"
                    aria-label="Kapat"
                >&times;</button>
            </div>
        </div>

        <!-- Modal İçeriği -->
        <div class="w-full h-full" >
            <div class="grid grid-cols-6 gap-1">
                <div class="p-2"></div>
                @foreach(['Pazartesi', 'Salı', 'Çarşamba', 'Perşembe', 'Cuma'] as $day)
                    <div class="text-center font-bold p-2 bg-gray-800 text-white">{{ $day }}</div>
                @endforeach

                @foreach($scheduleData as $time => $days)
                    <div class="text-center items-center justify-center font-bold bg-gray-200">{{ $time }}</div>
                    @foreach(range(0,4) as $day)
                        @if(isset($days[$day]) && is_array($days[$day]))
                            <div class="flex-grow-0 flex">
                                @foreach($days[$day] as $class)
                                    <div class="flex-1 border-l last:border-r p-1">
                                        @include('livewire.schedule.partials.editable-course-card', [
                                        'class' => $class,
                                        'time' => $time,
                                        'day' => $day,
                                        'scheduleId' => $scheduleId ?? -1
                                        ])
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="border bg-white" ></div>
                        @endif
                    @endforeach
                @endforeach

            </div>
        </div>

        <!-- Modal Footer -->
        <div class="flex justify-between mt-4 pt-4">
            <div class="w-full mr-2">
                    <textarea
                        class="rounded p-2 text-sm w-full h-12"
                        style="border: none"
                        placeholder="Açıklama giriniz..."
                    ></textarea>
            </div>
            <div data-html2canvas-ignore="true">
                <button
                    onclick="saveAndExport()"
                    class="px-2 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 flex items-center"
                    style="font-size: 12px;"
                >
                    <i class="fa-solid fa-download " ></i> PNG Olarak Kaydet
                </button>
            </div>
        </div>
    </div>
</div>

<script>
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
    /* Modal geçiş efekti */
    #editModal {
        transition: opacity 0.3s ease;
    }


</style>
