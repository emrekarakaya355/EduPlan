<div class="flex items-center space-x-2" wire:poll.5s="updateProgress">
    <div class="text-xs text-gray-600 text-center mt-1">
        Saat olarak Yerleştirilen: {{ $placed }} / {{ $total }}
    </div>
    <div class="relative w-5 h-5">
        <svg class="w-full h-full" viewBox="0 0 36 36">
            <path
                class="text-gray-300"
                stroke-width="4"
                stroke="currentColor"
                fill="none"
                d="M18 2.0845
                a 15.9155 15.9155 0 0 1 0 31.831
                a 15.9155 15.9155 0 0 1 0 -31.831"
            />
            <path
                class="text-blue-500"
                stroke-width="4"
                stroke-dasharray="{{ $percent }}, 100"
                stroke-linecap="round"
                stroke="currentColor"
                fill="none"
                d="M18 2.0845
                a 15.9155 15.9155 0 0 1 0 31.831
                a 15.9155 15.9155 0 0 1 0 -31.831"
            />
        </svg>
        <div class="absolute inset-0 flex items-center justify-center text-sm font-semibold text-gray-700">
            {{ $percent }}%
        </div>
    </div>

</div>
