<div class="relative bg-white"
     x-data="{ open: false }"
     x-on:click.outside="open = false">

    <div class="mb-4">
        <label for="search" class="block mb-1 font-medium text-gray-700">Akademisyen Ara</label>
        <input
            type="text"
            wire:model.live.debounce.100ms="search"
            id="search"
            placeholder="Akademisyen ismiyle ara..."
            class="w-full border border-gray-300 rounded px-3 py-2"
            x-on:focus="open = true"
            x-ref="searchInput"
            autocomplete="off"
        >
    </div>

    @if(strlen($search) > 1)
        @if($instructors->isNotEmpty())
            <ul
                x-show="open"
                x-transition.opacity
                class="fixed  z-10 bg-white border border-gray-300 rounded shadow-lg mt-1 max-h-60 overflow-y-auto"
            >
                @foreach($instructors as $instructor)
                    <li wire:click="$dispatch('instructorSelected', {id: {{$instructor->id}}, name: '{{$instructor->name}}' })"
                        x-on:click="open = false; $refs.searchInput.blur();"
                        class="p-2 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700 transition duration-150 ease-in-out"
                    >
                        {{ $instructor->name }}
                    </li>
                @endforeach
            </ul>
        @else
            <div x-show="open" class="absolute z-10 w-full bg-white border border-gray-300 rounded shadow-lg mt-1 p-2">
                <p class="text-gray-500">Sonuç bulunamadı.</p>
            </div>
        @endif
    @endif
</div>
