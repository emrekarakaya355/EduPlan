
@if(isset($courses) && is_array($courses))
    <div class="flex border overflow-hidden text-center align-middle" wire:key="cell-{{$time}}-{{$day}}">
        @foreach($courses as $course)
            @if ($viewMode === 'program')
                @include('livewire.schedule.partials.droppable-course-card', compact('course', 'day', 'time','scheduleId'))
            @else
                @include('livewire.schedule.partials.not-droppable-course-card', compact('course', 'day', 'time'))
            @endif
        @endforeach
    </div>
@else
    <div class="border bg-white dropzone"  wire:key="cell-{{$time}}-{{$day}}"
         data-day="{{ $day + 1 }}"
         data-hour="{{ $time }}"
         data-schedule="{{ $scheduleId ?? '-1' }}"
         ondragover="event.preventDefault()"
         ondrop="drop(event)">
    </div>

@endif
