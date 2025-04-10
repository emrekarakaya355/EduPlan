<div wire:key="{{ $time }}-{{ $course['id'] }}"
     class="border-l dropzone relative flex-1"
     draggable="true"
     data-id="{{ $course['id'] ?? '' }}"
     data-type="course"
     data-schedule="{{ $schedule->id ?? '' }}"
     data-day="{{ $day + 1 }}"
     data-hour="{{ $time }}"
     ondragstart="drag(event)"
     ondragover="event.preventDefault()"
     ondrop="drop(event)"
     ondblclick="Livewire.dispatch('open-instructor-modal', {
        instructorId: '{{ $course['instructor_id'] }}', instructorName: '{{ $course['instructor_name'] }}'})">

    @if($viewMode === 'program')
        <button wire:click="$dispatch('removeFromSchedule', { hour: '{{ $time }}', day: '{{ $day + 1 }}', courseId: '{{ $course['id'] ?? '' }}' })"
                class="absolute right-0 top-0 text-red-500" style="margin-top: -5px">
            <i class="fa-solid fa-square-xmark fa-fade"></i>
        </button>
    @endif

    <div class="text-sm name" style="pointer-events: none">{{ $course['class_code'] }}</div>
    <div class="name" style="pointer-events: none; font-size: xx-small">{{ $course['class_name'] }}</div>
    <div class="font-bold" style="pointer-events: none;font-size: xx-small">{{$viewMode === 'instructor' ?  $course['program_name'] :$course['instructor_name'] }}</div>
    <div style="pointer-events: none; font-size: xx-small">{{ $course['classrom_name'] ?? '(Derslik Sonra Belirtilecek)' }}</div>
    <div style="pointer-events: none; font-size: xx-small">{{ $course['building_name'] ?? '' }}</div>
</div>
