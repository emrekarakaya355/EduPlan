<div >
    <label for="instructor" class="block mb-1 font-medium text-gray-700"> Biriminde Ders Veren Akademisyenler</label>
    <select id="instructor"  wire:model.live="selectedInstructor"
            class="w-full border border-gray-300 rounded px-3 py-2">
        <option value="-1">-- Akademisyen Seçin --</option>
        @foreach($instructors as $instructor)
            <option value="{{ $instructor->id }}"  {{ $instructor->id == $selectedInstructor ? 'selected' : '' }}
                    data-selectedInstructorName = "{{$instructor->name }}">{{ $instructor->name }}</option>
        @endforeach
    </select>
</div>
