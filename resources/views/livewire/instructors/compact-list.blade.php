<div class="flex justify-between">
    <div class="w-1/3 max-w-sm mb-4">
        <label for="instructor" class="block mb-1 font-medium text-gray-700">{{$this->unit_name }} Biriminde Ders Veren Hocalar</label>
        <select id="instructor" wire:model="selectedInstructorId" wire:change="$dispatch('instructorSelected', {id: $event.target.value })"
                class="w-full border border-gray-300 rounded px-3 py-2">
            <option value="">-- Hoca Seçin --</option>
            @foreach($instructors as $instructor)
                <option value="{{ $instructor->id }}"  data-selectedInstructorName = "{{$instructor->name }}">{{ $instructor->name }}</option>
            @endforeach
        </select>
    </div>
    <!-- Arama Bileşeni -->
    <div class="w-1/3">
       <livewire:shared.instructor-search/>
    </div>
</div>
