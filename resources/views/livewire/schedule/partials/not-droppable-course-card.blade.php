<div wire:key="{{ $time }}-{{ $course['id'] }}"
     class="border-l  relative flex-1"
     data-id="{{ $course['id'] ?? '' }}"
     data-type="course"
     data-schedule="{{ $schedule->id ?? '' }}"
     data-day="{{ $day + 1 }}"
     data-hour="{{ $time }}">

    <div class="text-sm name" style="pointer-events: none">{{ $course['class_code'] }}</div>
    <div class="name" style="pointer-events: none; font-size: xx-small">{{ $course['class_name'] }}</div>
    <div class="font-bold" style="pointer-events: none;font-size: xx-small">{{$viewMode === 'instructor' ?  $course['program_name'] :$course['instructor_name'] }}</div>
    <div style="pointer-events: none; font-size: xx-small">{{ $course['classrom_name'] ?? '(Derslik Sonra Belirtilecek)' }}</div>
    <div style="pointer-events: none; font-size: xx-small">{{ $course['building_name'] ?? '' }}</div>
</div>
