<div     x-data
         x-on:keydown.escape.window="$wire.dispatch('close-modal')"
         x-on:click.self="$wire.dispatch('close-modal')"
         class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 w-3/4 max-h-screen overflow-auto">

        <div class=" text-center">
            <h3 class="text-lg font-bold text-center">{{$courseName}}</h3>
        </div>

        <div class="text-center">
            Ders Programı
        </div>

        <div class="grid grid-cols-{{count($days)+1}} gap-1 p-4 schedule-grid">

            <div class="p-2"></div>

            @foreach($days as $day)
                <div class="text-center font-bold p-2 bg-gray-800 text-white">{{ $day['label'] }}</div>
            @endforeach

            @foreach($scheduleData as $time => $daysData)
                @include('livewire.schedule.partials.time-row', ['time' => $time, 'days' => $daysData])
            @endforeach
        </div>
    </div>
</div>
