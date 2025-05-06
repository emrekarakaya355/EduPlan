<x-report-layout>

    @if($reportType)
        <livewire:is component="reports.{{ $reportType }}-list" wire:key="{{$reportType.}}"/>
    @endif
</x-report-layout>
