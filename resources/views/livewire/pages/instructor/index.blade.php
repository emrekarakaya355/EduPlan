<x-page-layout>
    <x-slot name="top" >
        <div class="flex justify-between space-x-8">
            <div  class="flex-1 justify-between">
                <div class="w-1/3 max-w-sm mb-4">
                    <livewire:instructors.filter-select :$unit_id :$semester :$year wire:key="select_{{$year.$unit_id.$semester.$selectedInstructorId}}"/>
                </div>
                <!--livewire:instructors.compact-list :$unit_id wire:key="{{$unit_id}}"/-->
                <div class="w-1/3" wire:ignore>
                    <livewire:shared.instructor-search />
                </div>
            </div>
            <div >
                <livewire:classrooms.filter-select wire:key="selectClassroom_{{$year.$unit_id.$semester.$selectedBuildingId}}"/>
            </div>
        </div>
    </x-slot>

    @if($selectedInstructorId)
        <livewire:schedule.instructor.schedule-chart  :instructorId="$selectedInstructorId" :instructorName="$selectedInstructorName" wire:key="instructor-{{ $selectedInstructorId }}" >
    @else
    <livewire:schedule.classroom.schedule-chart :classroomId="$selectedClassroomId"  wire:key="classroom-{{$selectedClassroomId}}"/>
    @endif

    <x-slot name="right">
        @if($selectedInstructorId)
            <livewire:courses.instructor-based :instructorId="$selectedInstructorId" wire:key="instructor-{{ $selectedInstructorId }}" />
        @endif
        @if($selectedBuildingId)
            <livewire:classrooms.total-list :$selectedBuildingId wire:key="list-{{$selectedBuildingId}}" />
        @endif
    </x-slot>
    <x-slot name="detay">
        <livewire:shared.dynamic-detail />
    </x-slot>
</x-page-layout>
