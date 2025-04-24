<div>
    <div class="w-full max-w-sm mb-4">
        <label for="instructor" class="block mb-1 font-medium text-gray-700">Hoca Seçin</label>
        <select id="instructor" wire:model="selectedInstructorId" wire:change="$dispatch('instructorSelected', {id: $event.target.value,selectedInstructorName: $event.target.options[$event.target.selectedIndex].dataset.selectedInstructorName })"
                class="w-full border border-gray-300 rounded px-3 py-2">
            <option value="">-- Hoca Seçin --</option>
            @foreach($instructors as $instructor)
                <option value="{{ $instructor->id }}"  data-selectedInstructorName = "{{$instructor->name }}">{{ $instructor->name }}</option>
            @endforeach
        </select>
    </div>
</div>
