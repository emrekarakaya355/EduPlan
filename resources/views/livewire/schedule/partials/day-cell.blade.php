@if(isset($courses) && is_array($courses))
    <div class="flex border overflow-hidden text-center align-middle">
        @foreach($courses as $course)
            @include('livewire.schedule.partials.course-card', compact('course', 'day', 'time'))
        @endforeach
    </div>
@else
    <div class="border bg-white dropzone"
         data-day="{{ $day + 1 }}"
         data-hour="{{ $time }}"
         data-schedule ="{{ $schedule?->id ?? '' }}"
         ondragover="event.preventDefault()"
         ondrop="drop(event)">
    </div>

@endif
