<div>
    <table class="min-w-full bg-white border border-gray-300 max-w-full">
        <!-- Tablo Başlığı -->
        <thead>
        <tr>
            <th class="py-2 px-4 border-b">Program / Sınıf</th>
            @foreach (['Pazartesi', 'Salı','Çarşamba','Perşembe','Cuma','Cumartesi'] as $day)
                <th class="border" colspan="11">{{ $day }}</th>
            @endforeach
        </tr>
        <tr>
            <th></th>
            @foreach (['Pazartesi', 'Salı','Çarşamba','Perşembe','Cuma','Cumartesi'] as $day)
                @foreach (range(8, 18) as $hour)
                    <th class="border  text-sm">{{ $hour }}</th>
                @endforeach

             @endforeach
        </tr>
        </thead>

        <tbody>
        @foreach ($programSchedules as $programName => $schedules)
            <tr>
                <!-- Program adı -->
                <td class="border py-2 px-4 text-left text-sm" style="white-space: nowrap">{{ str($programName)->words(2)}}</td>

                @foreach (['Pazartesi', 'Salı', 'Çarşamba', 'Perşembe', 'Cuma', 'Cumartesi'] as $day)
                    @foreach (range(8, 18) as $hour)
                        <td class="border">
                            @foreach ($schedules as $schedule)
                                @if ($schedule->day == $day && $schedule->hour == $hour)
                                    <div>
                                        <strong>{{ $schedule->program_name }}</strong><br>
                                        {{ $schedule->course_name }}
                                    </div>
                                @endif
                            @endforeach
                        </td>
                    @endforeach
                @endforeach
            </tr>
        @endforeach
        </tbody>
    </table>


    <x-slot name="right">
        <livewire:courses.course-compact-list :courses="$this->courseClasses"/>
    </x-slot>

    <x-slot name="top">
        <livewire:classrooms.block-list />
    </x-slot>

    <x-slot name="detay">
        <livewire:dynamic-detail />
    </x-slot>
</div>
