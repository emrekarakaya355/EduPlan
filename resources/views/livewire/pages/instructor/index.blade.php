<x-page-layout>
    <x-slot name="top" >
        <div class="flex justify-between ">
            <div  class="flex-1 justify-between ml-4">
                <div class="w-1/3  mb-4">
                    <livewire:instructors.filter-select :$unit_id :$semester :$year wire:key="select_{{$year.$unit_id.$semester}}"/>
                </div>
                <!--livewire:instructors.compact-list :$unit_id wire:key="{{$unit_id}}"/-->
                <div class="w-1/3 relative z-40">
                    <livewire:shared.instructor-search />
                </div>
            </div>
            <div class="mr-2">
                <livewire:classrooms.filter-select wire:key="selectClassroom_{{$year.$unit_id.$semester.$selectedBuildingId}}"/>
            </div>
        </div>
    </x-slot>

    @if(isset($selectedClassroomId) && $selectedClassroomId)
        <livewire:schedule.classroom.schedule-chart :classroomId="$selectedClassroomId"  wire:key="classroom-{{$selectedClassroomId}}"/>
    @else
        <livewire:schedule.instructor.schedule-chart  :instructorId="$selectedInstructorId" :instructorName="$selectedInstructorName" wire:key="instructor-schedule-{{ $selectedInstructorId }}" >
    @endif

    <x-slot name="right">
        @if($selectedInstructorId)
            <livewire:courses.instructor-based :instructorId="$selectedInstructorId" wire:key="instructor-{{ $selectedInstructorId }}" />
        @endif
        @if($selectedBuildingId)
            <livewire:classrooms.total-list :$selectedBuildingId wire:key="list-{{$selectedBuildingId}}" />
        @endif
    </x-slot>

</x-page-layout>
