<x-report-layout>
    @if($selectedReportType)
        <livewire:is component="reports.{{ $selectedReportType }}-list" wire:key="{{$selectedReportType}}-list"/>
    @endif
</x-report-layout>
