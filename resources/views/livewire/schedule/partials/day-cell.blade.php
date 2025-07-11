    @if(isset($classes) && is_array($classes))
        <div class="flex flex-col  gap-1 border overflow-hidden text-center w-full rounded-lg "  wire:key="cell-{{$time}}-{{$day}}">
            @foreach($classes as $class)
                @if ($viewMode === 'program')
                    @include('livewire.schedule.partials.droppable-course-card', compact('class', 'day', 'time','scheduleId'))
                @else
                    @include('livewire.schedule.partials.not-droppable-course-card', compact('class', 'day', 'time'))
                @endif
            @endforeach
        </div>
    @else
        <div class="border bg-white dropzone w-full rounded-lg"  wire:key="cell-{{$time}}-{{$day}}"
             data-day="{{ $day + 1 }}"
             data-hour="{{ $time }}"
             data-schedule="{{ $scheduleId ?? '-1' }}"
             ondrop="drop(event)"
             ondragover="allowDrop(event)"
             ondragleave="dragLeave(event)">
        </div>

    @endif
