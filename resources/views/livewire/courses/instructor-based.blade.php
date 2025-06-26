<div class="course-section p-4 bg-white dark:bg-gray-900">
    <div>
        <div class="courses-container grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3 mt-4">
            @foreach($this->courses as $courseClass)
                <div
                    class="course-item
                           bg-white dark:bg-gray-800 p-3 rounded-lg shadow-md border border-gray-200 dark:border-gray-700
                           flex flex-col gap-1 cursor-grab transition-transform duration-300 hover:scale-[1.02] hover:bg-gray-50 dark:hover:bg-gray-700
                           text-sm"
                >
                    <div class="course-details flex items-center justify-between text-gray-700 dark:text-gray-200">
                        <span class="course-name font-semibold flex-1 truncate">{{ $courseClass->course->code }}</span>
                        @if($courseClass->unscheduledHours > 0)
                            <span class="course-duration text-xs font-medium bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300 px-2 py-0.5 rounded-full ml-2 flex-shrink-0">
                                {{ $courseClass->unscheduledHours }}h
                            </span>
                        @else
                            <span class="course-duration text-xs font-medium bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300 px-2 py-0.5 rounded-full ml-2 flex-shrink-0">
                                Atanmış
                            </span>
                        @endif
                    </div>
                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                        <p class="font-medium truncate">{{ $courseClass->course->name }}</p>
                        {{-- EK BİLGİLER BURADA BAŞLAR --}}
                        <div class="flex flex-wrap gap-x-2 mt-1"> {{-- Bilgileri yan yana dizmek için flex ve gap-x kullanıldı --}}
                                <p><span class="font-semibold">Pratik:</span> {{ $courseClass->practical_duration }}s</p>
                                <p><span class="font-semibold">Teori:</span> {{ $courseClass->theoretical_duration }}s</p>

                        </div>
                        @if($courseClass->grade)
                            <p><span class="font-semibold">Sınıf:</span> {{ $courseClass->grade }}</p>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
        <style>
            .courses-container {
                grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            }

            @media (min-width: 640px) {
                .courses-container {
                    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
                }
            }

            @media (min-width: 768px) { /* md */
                .courses-container {
                    grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
                }
            }

            @media (min-width: 1024px) { /* lg */
                .courses-container {
                    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
                }
            }

            @media (min-width: 1280px) { /* xl */
                .courses-container {
                    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
                }
            }

            /* Eğer hala dikeyde çok yer kaplıyorlarsa, course-item'ın paddingini azaltabilirsiniz. */
            .course-item {
                /* min-height: 80px; /* Ya da sabit, küçük bir min-height verebilirsiniz */
            }
        </style>
    </div>
</div>
