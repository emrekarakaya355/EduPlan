@if($showTime ?? true)
    <div class="text-center font-bold p-2 bg-gray-200 col-span-6">
        {{ $time }}
    </div>
@endif

@foreach(range(0, 5) as $day)
    @include('livewire.schedule.partials.day-cell', [
        'day' => $day,
        'time' => $time,
        'courses' => $days[$day] ?? null
    ])
@endforeach
