<div>
    <!--livewire:schedule.instructor.schedule-chart /-->

     <x-slot name="top">
         <livewire:instructors.compact-list :instructors="$this->instructors"/>

    </x-slot>
    <x-slot name="right">
        <livewire:courses.instructor-based />
    </x-slot>

</div>
