<div wire:key="{{ $time }}-{{ $class['id'] }}"
     class="border-l  relative flex-1"
     data-id="{{ $class['id'] ?? '' }}"
     data-type="course"
     data-schedule="{{ $schedule->id ?? '' }}"
     data-day="{{ $day + 1 }}"
     data-hour="{{ $time }}"
     style="background-color: {{ $class['color'] ?? '#FFFFFF' }};"
>

    <div class="text-sm name" style="pointer-events: none">{{ $class['class_code'] }}</div>
    <div class="name" style="pointer-events: none; font-size: xx-small">{{ $class['class_name'] }}</div>
    <div class="font-bold" style="pointer-events: none;font-size: xx-small">{{$viewMode === 'instructor' ?  $class['program_name'] :$class['instructor_name'] }}</div>
    <div style="pointer-events: none; font-size: xx-small">{{ $class['classrom_name'] ?? '(Derslik Sonra Belirtilecek)' }}</div>
    <div style="pointer-events: none; font-size: xx-small">{{ $class['building_name'] ?? '' }}</div>
</div>
