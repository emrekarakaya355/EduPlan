<div class="classroom-grid-container p-4 dark:bg-gray-900 bg-white">
    {{-- 'min-h-[calc(100vh-200px)]' Sınıfı BURADAN KALDIRILDI! --}}
    @foreach ($classrooms as $classroom)
        @php
            $usagePercent = min(100, ($classroom->UniqueUsedTimeSlotsCount / 45) * 100);
            if ($usagePercent < 50) {
                $progressColor = '#38bdf8'; // Tailwind blue-400
            } elseif ($usagePercent < 80) {
                $progressColor = '#facc15'; // Tailwind yellow-400
            } else {
                $progressColor = '#f87171'; // Tailwind red-400
            }
            $progressBgColor = '#e5e7eb'; // Tailwind gray-200
            $progressBgColorDark = '#4b5563'; // Tailwind gray-600
        @endphp
        <div
            wire:key="classroom-class-{{ $classroom['id'] }}"
            data-id="{{ $classroom['id'] }}"
            class="classroom-item
                   {{ $classroom['id'] == $selectedClassroomId ? 'selected-classroom' : '' }}
                   flex flex-col p-3 rounded-lg shadow-md transition-all duration-300 cursor-pointer
                   bg-white hover:bg-gray-50 border border-gray-200
                   dark:bg-gray-800 dark:hover:bg-gray-700 dark:border-gray-700 relative"
            data-type="classroom"
            wire:click="selectedClassroom({{ $classroom['id'] }})"
            wire:mouseenter.debounce.250="$dispatch('showDetail', {
                                                'Derslik Adı' : '{{ addslashes($classroom['name']) }}',
                                                'Fakülte' : '{{ addslashes($classroom['building']['campus']['name']) }}',
                                                'Bina' : '{{ addslashes($classroom['building']['name']) }}',
                                                'Sınıf Türü' : '{{ addslashes($classroom['type']) }}',
                                                'Ders Kapasitesi' : '{{ addslashes($classroom['class_capacity']) }} kişi',
                                                'Sınav Kapasitesi' : '{{ addslashes($classroom['exam_capacity']) }} kişi',
                                            })">

            <div class="flex items-center justify-between mb-1">
                <p class="classroom-name text-sm font-semibold text-gray-800 dark:text-gray-100 truncate flex-grow">
                    {{ $classroom['name'] }}
                </p>
                <div class="circular-progress ml-2 flex-shrink-0"
                     style="--progress: {{ $usagePercent }}%;
                            --progress-color: {{ $progressColor }};
                            --progress-bg-color: {{ $progressBgColor }};
                            --progress-bg-color-dark: {{ $progressBgColorDark }};">
                    <span class="text-xs font-bold text-gray-700 dark:text-gray-200">{{ number_format($usagePercent, 0) }}%</span>
                </div>
            </div>

            <div class="text-xs text-gray-600 dark:text-gray-300">
                <p><span class="font-medium">Bina:</span> {{ $classroom['building']['name'] }}</p>
                <p><span class="font-medium">Tür:</span> {{ $classroom['type'] }}</p>
                <p><span class="font-medium">Kapasite:</span> {{ $classroom['class_capacity'] }}</p>
            </div>

        </div>
    @endforeach
    <style>
        /* Sadece dairesel ilerleme çubuğu için özel CSS */
        .classroom-grid-container {
            display: grid;
            gap: 0.75rem; /* gap-3 (12px) - daha küçük boşluk */
            grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); /* Daha küçük minimum genişlik */
        }

        @media (min-width: 640px) { /* Small screens and up */
            .classroom-grid-container {
                grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            }
        }

        @media (min-width: 768px) { /* Medium screens and up */
            .classroom-grid-container {
                grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            }
        }

        @media (min-width: 1024px) { /* Large screens and up */
            .classroom-grid-container {
                grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            }
        }

        /* Kartın kendisi için Tailwind sınıfları kullanıldı */
        .classroom-item {
            padding: 0.75rem; /* p-3 (12px) - daha az padding */
        }

        .classroom-item.selected-classroom {
            border-color: #2563eb; /* Tailwind blue-600 */
            background-color: #e0f2fe; /* Tailwind blue-50 */
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2); /* Daha belirgin bir gölge */
            transform: translateY(-2px); /* Seçildiğinde hafif yukarı kalksın */
        }

        .dark .classroom-item.selected-classroom {
            background-color: #1e3a8a; /* Tailwind blue-900 */
            border-color: #3b82f6; /* Tailwind blue-500 */
        }

        /* Dairesel İlerleme Çubuğu - Sadece bunun için özel CSS kullanmak daha iyi */
        .circular-progress {
            --size: 36px; /* Biraz daha küçük */
            --thickness: 3px; /* Biraz daha ince */
            width: var(--size);
            height: var(--size);
            border-radius: 50%;
            background: conic-gradient(var(--progress-color) calc(var(--progress)), var(--progress-bg-color) 0);
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            box-sizing: border-box;
            transition: background 0.5s ease;
        }

        /* Dark mode için dairesel ilerleme çubuğu arka planı */
        .dark .circular-progress {
            background: conic-gradient(var(--progress-color) calc(var(--progress)), var(--progress-bg-color-dark) 0);
        }

        .circular-progress::before {
            content: "";
            position: absolute;
            width: calc(var(--size) - var(--thickness)*2);
            height: calc(var(--size) - var(--thickness)*2);
            background: #fff; /* İç daire rengi */
            border-radius: 50%;
            z-index: 1;
        }

        .dark .circular-progress::before {
            background: #1f2937; /* Dark mode iç daire rengi */
        }

        .circular-progress span {
            z-index: 2;
            position: relative;
            font-size: 0.65rem; /* Tailwind'in 'text-xs'ten biraz daha küçük olabilir */
        }

    </style>
</div>
