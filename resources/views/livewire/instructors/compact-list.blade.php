<div>
    <div class="flex flex-wrap gap-2">
        @if($instructors)
            @foreach($instructors as $instructor)
                <div wire:click="selectInstructor({{ $instructor->id }})"
                     class="px-4 py-2 border rounded cursor-pointer transition-colors
                            {{ $selectedInstructorId == $instructor->id ? 'bg-blue-500 text-white' : 'bg-white hover:bg-gray-100' }}">
                    {{ $instructor->name }}
                </div>
            @endforeach
        @endif
    </div>
</div>
