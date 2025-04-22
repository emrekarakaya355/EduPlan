<x-page-layout>


    <livewire:schedule.instructor.schedule-chart  :instructorId="$selectedInstructorId" :instructorName="$selectedInstructorName" >

     <x-slot name="top">
         <livewire:instructors.compact-list/>
    </x-slot>
    <x-slot name="right">
        <livewire:courses.instructor-based :instructorId="$selectedInstructorId" wire:key="instructor-{{ $selectedInstructorId }}" />
    </x-slot>
</x-page-layout>
