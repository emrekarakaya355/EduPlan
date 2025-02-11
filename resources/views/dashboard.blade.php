<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>


        {{ __("You're logged in!") }}
    <x-slot name="right">
        <div>
            <h2>
                Sağ
            </h2>
        </div>
    </x-slot>
    <x-slot name="top">
        <div>
            <h2>
                Üst
            </h2>
        </div>
    </x-slot>


    <x-slot name="foot">
        <div>
            <h2>
                alt
            </h2>
        </div>
    </x-slot>
</x-app-layout>
