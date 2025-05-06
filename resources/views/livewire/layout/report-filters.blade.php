<aside class="fixed left-0 top-0 h-full w-64 bg-gray-900 text-white shadow-lg pt-16 z-40 transition-transform transform"
       :class="{ '-translate-x-full': !sidebarOpen, 'translate-x-0': sidebarOpen }">
    <div class="w-64 bg-gray-900 p-4 text-white">
        <label class="block mb-2">Rapor Türü</label>
        <select wire:model.live.debounce="selectedReportType" class="w-full p-2 border border-gray-700 bg-gray-800 text-white rounded">
            <option value="">Rapor Türünü Seçiniz</option>
            @foreach($reportTypes as $key => $label)
                <option value="{{ $key }}">{{ $label }}</option>
            @endforeach
        </select>
    </div>

    @if($selectedReportType)
        <livewire:is component="shared.filters.{{ $selectedReportType }}-filter" wire:key="{{$selectedReportType}}"/>
    @endif
</aside>
