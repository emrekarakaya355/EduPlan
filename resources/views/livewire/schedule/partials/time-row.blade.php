@if($showTime ?? true)
    <div class="text-center font-bold p-2 bg-gray-200">
        {{ $time }}
    </div>
@endif

@foreach(range(0,4) as $day)
    @include('livewire.schedule.partials.day-cell', [
        'day' => $day,
        'time' => $time,
        'classes' => $days[$day] ?? null
    ])
@endforeach
