<div class="relative  bg-white">
    <div class="mb-4">
        <label for="search" class="block mb-1 font-medium text-gray-700">Hoca Ara</label>
        <input type="text" wire:model.live.debounce.300ms="search" id="search" placeholder="Hoca ismiyle ara..."
               class="w-full border border-gray-300 rounded px-3 py-2">
    </div>

    @if(strlen($search) > 1)
        @if($instructors->isNotEmpty())
            <ul class="fixed bg-white">
                @foreach($instructors as $instructor)
                    <li wire:click="$dispatch('instructorSelected', {id: {{$instructor->id}} })"
                        class="p-2 border rounded cursor-pointer hover:bg-gray-100 transition">
                        {{ $instructor->name }}
                    </li>
                @endforeach
            </ul>
        @else
            <p class="text-gray-500">Sonuç bulunamadı.</p>
        @endif
    @endif
</div>
