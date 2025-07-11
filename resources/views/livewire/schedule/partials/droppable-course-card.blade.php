<div wire:key="{{ $time }}-{{ $class['id'] }}"
     class="border-l dropzone relative flex flex-col justify-center flex-1 items-center p-2 rounded-lg "

     draggable="true"
     data-id="{{ $class['id'] ?? '' }}"
     data-external="{{ $class['external_id'] ?? '' }}"
     data-type="course"
     data-schedule="{{ $scheduleId ?? '' }}"
     data-day="{{ $day + 1 }}"
     data-hour="{{ $time }}"
     data-constraints="{{$class['constraints']}}"
     ondragstart="drag(event)"
     ondrop="drop(event)"
     ondragover="allowDrop(event)"
     ondragleave="dragLeave(event)"
     ondragend="dragEnd(event)"
     style="background-color: {{ $class['color'] ?? '#FFFFFF' }};"
     @if(!empty($class['instructor_id']))
         ondblclick="Livewire.dispatch('open-instructor-modal', {
                instructorId: '{{ $class['instructor_id'] }}',
                instructorName: '{{ $class['instructor_name'] }}'
    })"
    @endif
    >
    @if(!empty($class['commonLesson']))
        <div class="absolute top-0 left-0 text-blue-600" style=" z-index: 10;">
            <i class="fa-solid fa-link fa-xs" title="Birleştirilmiş Ders"></i>
        </div>
    @endif
    @if($viewMode === 'program')
        <button data-html2canvas-ignore="true" wire:click="$dispatch('removeFromSchedule', { hour: '{{ $time }}', day: '{{ $day + 1 }}', classId: '{{ $class['id'] ?? '' }}' })"
                class="absolute right-0 top-0 text-red-500 " style="margin-top: -5px">
            <i class="fa-solid fa-square-xmark fa-fade"></i>
        </button>
    @endif

    <div class="text-sm font-semibold name" style="pointer-events: none">{{ $class['class_code'] }}</div>
    <div class="name" style="pointer-events: none; font-size: xx-small">{{ $class['class_name'] }}</div>
    <div class="font-medium" style="pointer-events: none;font-size: xx-small">{{$viewMode === 'instructor' ?  $class['program_name'] :$class['instructor_name'] }}</div>
    <div style="pointer-events: none; font-size: x-small">
        {{ $class['classrom_name'] ?? '(Derslik Sonra Belirtilecek)' }}
    </div>
    <div style="pointer-events: none;
         font-size: x-small">
        {{ $class['building_name'] ?? '' }}
    </div>

        <style>
            .drop-forbidden {
                background-color: #fee2e2 !important;
                border: 2px dashed #dc2626 !important;
                position: relative;
            }

            .drop-forbidden::before {
                content: '❌';
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                font-size: 20px;
                z-index: 10;
            }
        </style>
</div>
