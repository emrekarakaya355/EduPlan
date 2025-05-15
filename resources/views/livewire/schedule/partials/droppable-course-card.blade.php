<div wire:key="{{ $time }}-{{ $class['id'] }}"
     class="border-l dropzone relative flex-1"
     draggable="true"
     data-id="{{ $class['id'] ?? '' }}"
     data-external="{{ $class['external_id'] ?? '1' }}"
     data-type="course"
     data-schedule="{{ $scheduleId ?? '' }}"
     data-day="{{ $day + 1 }}"
     data-hour="{{ $time }}"
     ondragstart="drag(event)"
     ondragover="event.preventDefault()"
     ondrop="drop(event)"
     style="background-color: {{ $class['color'] ?? '#FFFFFF' }};"
     @if(!empty($class['instructor_id']))
         ondblclick="Livewire.dispatch('open-instructor-modal', {
                instructorId: '{{ $class['instructor_id'] }}',
                instructorName: '{{ $class['instructor_name'] }}'
    })"
    @endif
    >

    @if($viewMode === 'program')
        <button data-html2canvas-ignore="true" wire:click="$dispatch('removeFromSchedule', { hour: '{{ $time }}', day: '{{ $day + 1 }}', classId: '{{ $class['id'] ?? '' }}' })"
                class="absolute right-0 top-0 text-red-500 " style="margin-top: -5px">
            <i class="fa-solid fa-square-xmark fa-fade"></i>
        </button>
    @endif

    <div class="text-sm name" style="pointer-events: none">{{ $class['class_code'] }}</div>
    <div class="name" style="pointer-events: none; font-size: xx-small">{{ $class['class_name'] }}</div>
    <div class="font-bold" style="pointer-events: none;font-size: xx-small">{{$viewMode === 'instructor' ?  $class['program_name'] :$class['instructor_name'] }}</div>
    <div style="pointer-events: none; font-size: xx-small">
        {{ $class['classrom_name'] ?? '(Derslik Sonra Belirtilecek)' }}
    </div>
    <div style="pointer-events: none;
         font-size: xx-small">
        {{ $class['building_name'] ?? '' }}
    </div>
</div>
