<div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 w-3/4 max-h-screen overflow-auto">
        <div class="flex justify-between items-center mb-4">
            <button wire:click="$set('showInstructorModal', false)" class="text-gray-500 hover:text-gray-700">
                &times;
            </button>
        </div>

        <div class="grid grid-cols-6 gap-1 schedule-grid">
            @foreach($scheduleData as $time => $days)
                @include('livewire.schedule.partials.time-row', [
                    'time' => $time,
                    'days' => $days,
                    'showTime' => true
                ])
            @endforeach
        </div>
    </div>
</div>
