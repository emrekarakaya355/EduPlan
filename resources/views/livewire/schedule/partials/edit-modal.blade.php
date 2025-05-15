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
        <div class="flex justify-between items-center">
            <div>
                <img src="{{ asset('assets/images/logos/kucuk_logo.png') }}" alt="Logo" class="h-24 w-auto">
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
            <div class="grid gap-1"  style="grid-template-columns: 120px repeat(5, 1fr);">
                <div class="p-2"></div>
                @foreach(['Pazartesi', 'Salı', 'Çarşamba', 'Perşembe', 'Cuma'] as $day)
                    <div class="text-center font-bold bg-gray-800 text-white">{{ $day }}</div>
                @endforeach

                @foreach($scheduleData as $time => $days)
                    <div class="text-center flex items-center justify-center font-bold bg-gray-200">{{ $time }}</div>
                    @foreach(range(0,4) as $day)
                        @if(isset($days[$day]) && is_array($days[$day]))
                            <div class="flex-grow-0 flex">
                                @foreach($days[$day] as $class)
                                    <div class="flex-1">
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
                            <div class="cell" ></div>
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
                <button
                    onclick="printCompactSchedule()"
                    class="px-2 py-1 bg-green-600 text-white rounded hover:bg-green-700 flex items-center ml-2"
                    style="font-size: 12px;"
                    data-html2canvas-ignore="true"
                >
                    <i class="fa-solid fa-print mr-1"></i> Yazdır
                </button>
            </div>
        </div>
    </div>
@script
<script>

    function printCompactSchedule() {
        // Grid içeriğini doğrudan oluşturarak daha kompakt bir düzen sağlayalım
        const gridContainer = document.querySelector('#editModal > div > div.w-full.h-full > div.grid');

        // Başlık bilgilerini alalım
        const titleContainer = document.querySelector('#editModal > div > div.flex.items-center.flex-col');
        const titleText = titleContainer ? titleContainer.textContent.trim() : "Ders Programı";

        // Haftanın günleri
        const days = ['Pazartesi', 'Salı', 'Çarşamba', 'Perşembe', 'Cuma'];

        // Yazdırma penceresi açalım
        const printWindow = window.open('', '_blank', 'width=800,height=600');

        // Tabloyu oluştur
        let tableHTML = '<table border="1" cellspacing="0" cellpadding="2" style="width:100%; border-collapse: collapse; table-layout: fixed;">';

        // Başlık satırı
        tableHTML += '<tr><th style="width:60px;"></th>';
        days.forEach(day => {
            tableHTML += `<th style="background-color:#1f2937; color:white; font-size:8px; padding:2px;">${day}</th>`;
        });
        tableHTML += '</tr>';

        // Saat satırlarını ve içerikleri ekleyelim
        const timeSlots = [];
        // Mevcut zaman dilimlerini al
        gridContainer.querySelectorAll('.bg-gray-200').forEach(el => {
            const timeText = el.textContent.trim();
            if (timeText && !timeSlots.includes(timeText)) {
                timeSlots.push(timeText);
            }
        });

        // Her saat için satır oluştur
        timeSlots.forEach(time => {
            tableHTML += `<tr>
            <td style="background-color:#f3f4f6; font-weight:bold; font-size:7px; text-align:center;">${time}</td>`;

            // Her gün için o saatteki dersleri ekle
            for (let dayIndex = 0; dayIndex < 5; dayIndex++) {
                // İlgili hücreleri bul (bu kısım örnek olarak verilmiştir, gerçek yapınıza göre düzenlemeniz gerekebilir)
                let cellContent = '';

                // İlgili hücreyi grid içinde bulmaya çalışalım
                const cellIndex = timeSlots.indexOf(time) * 6 + dayIndex + 1 + 6; // Başlık satırını hesaba kat
                const cellDiv = gridContainer.children[cellIndex]?.querySelector('.cell');

                if (cellDiv) {
                    // Hücre içeriğini daha kompakt formatta oluştur
                    const classCode = cellDiv.querySelector('.text-sm.name')?.textContent.trim() || '';
                    const className = cellDiv.querySelector('.name:not(.text-sm)')?.textContent.trim() || '';
                    const instructor = cellDiv.querySelector('.font-bold.abbreviation')?.textContent.trim() || '';
                    const classroom = cellDiv.querySelectorAll('div')[3]?.textContent.trim() || '';
                    const building = cellDiv.querySelectorAll('div')[4]?.textContent.trim() || '';

                    if (classCode || className || instructor || classroom || building) {
                        cellContent = `
                        <div style="font-size:6px; line-height:1.0; overflow:hidden;">
                            <div style="font-weight:bold; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">${classCode}</div>
                            <div style="white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">${className}</div>
                            <div style="font-weight:bold;">${instructor}</div>
                            <div>${classroom}</div>
                            <div style="font-size:5px;">${building}</div>
                        </div>
                    `;
                    }
                }

                tableHTML += `<td style="height:40px; padding:0; vertical-align:middle; text-align:center;">${cellContent}</td>`;
            }

            tableHTML += '</tr>';
        });

        tableHTML += '</table>';

        // Yazdırma içeriğini hazırlayalım
        printWindow.document.write(`
        <html>
            <head>
                <title>Ders Programı</title>
                <style>
                    @media print {
                        body {
                            font-family: Arial, sans-serif;
                            font-size: 7px;
                            margin: 5px;
                            padding: 0;
                        }

                        table {
                            width: 100%;
                            border-collapse: collapse;
                        }

                        td, th {
                            border: 1px solid #d1d5db;
                        }

                        th {
                            background-color: #1f2937 !important;
                            color: white !important;
                        }

                        tr:first-child th:first-child {
                            background-color: white !important;
                            color: black !important;
                        }

                        td:first-child {
                            background-color: #f3f4f6 !important;
                        }

                        div {
                            margin: 0;
                            padding: 0;
                        }

                        .title {
                            text-align: center;
                            font-weight: bold;
                            font-size: 9px;
                            margin-bottom: 5px;
                        }

                        @page {
                            size: A4 landscape;
                            margin: 0.5cm;
                        }
                    }
                </style>
            </head>
            <body>
                <div class="title">${titleText}</div>
                ${tableHTML}
            </body>
        </html>
    `);

        printWindow.document.close();

        // Pencere yüklendikten sonra yazdırın
        printWindow.onload = function() {
            printWindow.focus();
            printWindow.print();
            printWindow.onafterprint = function() {
                printWindow.close();
            };
        };
    }
</script>
@endscript
<style>
    /* Modal geçiş efekti */
    #editModal {
        transition: opacity 0.3s ease;
    }


</style>
</div>
