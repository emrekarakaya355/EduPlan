<div>

    <div class="flex items-center gap-2">
        <button wire:click="selectTab('course')"
                class="w-full {{ $activeTab === 'course' ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-800' }}">
            Dersler
        </button>
        <button wire:click="selectTab('classroom')"
                class="w-full {{ $activeTab === 'classroom' ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-800' }}">
            Derslikler
        </button>
    </div>
    <div class="min-h-32">
        <livewire:shared.dynamic-detail />
    </div>
    <div class="mt-4">
        @if ($activeTab === 'course')
            <div>
                <livewire:courses.program-based />
            </div>
        @elseif ($activeTab === 'classroom')
            <div>
                <livewire:classrooms.clasrooms-list />

            </div>
        @endif
    </div>
</div>
