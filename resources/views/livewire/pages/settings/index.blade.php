<x-plain-layout>
    <div class="container mx-auto p-4">
        <h2 class="text-2xl font-bold mb-4">Ayarlar</h2>

        <div class="border-b border-gray-200 dark:border-gray-700 mb-4">
            <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="default-tab" data-tabs-toggle="#default-tab-content" role="tablist">
                <li class="me-2" role="presentation">
                    <button class="inline-block p-4 border-b-2 rounded-t-lg
                        {{ $activeTab === 'time-settings' ? 'text-blue-600 border-blue-600 dark:text-blue-500 dark:border-blue-500' : 'hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300' }}"
                            id="time-settings-tab" type="button" role="tab" aria-controls="time-settings" aria-selected="{{ $activeTab === 'time-settings' ? 'true' : 'false' }}"
                            wire:click="setActiveTab('time-settings')">
                        Zaman Ayarları
                    </button>
                </li>
                <li class="me-2" role="presentation">
                    <button class="inline-block p-4 border-b-2 rounded-t-lg
                        {{ $activeTab === 'authorization-manager' ? 'text-blue-600 border-blue-600 dark:text-blue-500 dark:border-blue-500' : 'hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300' }}"
                            id="authorization-manager-tab" type="button" role="tab" aria-controls="authorization-manager" aria-selected="{{ $activeTab === 'authorization-manager' ? 'true' : 'false' }}"
                            wire:click="setActiveTab('authorization-manager')">
                        Yetkilendirme Yöneticisi
                    </button>
                </li>
                <li class="me-2" role="presentation">
                    <button class="inline-block p-4 border-b-2 rounded-t-lg
                        {{ $activeTab === 'instructor-constraints' ? 'text-blue-600 border-blue-600 dark:text-blue-500 dark:border-blue-500' : 'hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300' }}"
                            id="instructor-constraints-tab" type="button" role="tab" aria-controls="instructor-constraints" aria-selected="{{ $activeTab === 'instructor-constraints' ? 'true' : 'false' }}"
                            wire:click="setActiveTab('instructor-constraints')">
                        Eğitmen Kısıtlamaları
                    </button>
                </li>

            </ul>
        </div>

        <div id="default-tab-content">
            <div class="{{ $activeTab === 'time-settings' ? 'block' : 'hidden' }}" id="time-settings" role="tabpanel" aria-labelledby="time-settings-tab">
                <livewire:settings.time-settings/>
            </div>
            <div class="{{ $activeTab === 'authorization-manager' ? 'block' : 'hidden' }}" id="authorization-manager" role="tabpanel" aria-labelledby="authorization-manager-tab">
                <livewire:settings.authorization-manager />
            </div>
            <div class="{{ $activeTab === 'instructor-constraints' ? 'block' : 'hidden' }}" id="instructor-constraints" role="tabpanel" aria-labelledby="instructor-constraints-tab">
                {{-- Make sure to handle the instructor-id prop correctly in the instructor-constraints component --}}
                <livewire:settings.instructor-constraints $instructor-id="-1"/>
            </div>

        </div>
    </div>
</x-plain-layout>
