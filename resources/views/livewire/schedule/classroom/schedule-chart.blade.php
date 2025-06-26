<div
@if($asModal)
     x-data
     x-on:keydown.escape.window="$wire.dispatch('close-modal')"
     x-on:click.self="$wire.dispatch('close-modal')"
     class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50 p-4 sm:p-6"
@endif
>
    <div class="bg-white rounded-lg p-6
    @if($asModal)
        w-7/8 md:w-7/8 lg:w-7/8 xl:w-7/8
        max-h-[90vh]
        overflow-hidden
        @endif
        ">
        <div class=" text-center">
            <h3 class="text-lg font-bold text-center">{{$classroomName}}</h3>
        </div>
        <div class="text-center">
            Ders Programı
        </div>
        <div class="overflow-y-auto max-h-[calc(90vh-120px)] custom-scrollbar"> {{-- Buradaki height değeri ayarlanmalı --}}

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
</div>
