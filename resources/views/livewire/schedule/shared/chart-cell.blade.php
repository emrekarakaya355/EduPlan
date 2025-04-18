<div>
    <div class="border min-h-[50px] bg-gray-100 text-center align-middle dropzone draggable">
        @foreach ($slots as $slot)
            <div class="font-bold">{{ $slot->course->name }}</div>
            <div class="text-sm">Teacher: {{ $slot->course->teacher->name }}</div>
        @endforeach
    </div>
</div>
