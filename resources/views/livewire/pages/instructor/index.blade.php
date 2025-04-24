<x-page-layout>
    <x-slot name="top">
        <livewire:instructors.compact-list :$instructors :$unit_id />
    </x-slot>

    <livewire:schedule.instructor.schedule-chart  :instructorId="$selectedInstructorId" :instructorName="$selectedInstructorName" wire:key="instructor-{{ $selectedInstructorId }}" >

    <x-slot name="right">
        <livewire:courses.instructor-based :instructorId="$selectedInstructorId" wire:key="instructor-{{ $selectedInstructorId }}" />
    </x-slot>
    <x-slot name="detay">
        <livewire:shared.dynamic-detail />
    </x-slot>
</x-page-layout>
