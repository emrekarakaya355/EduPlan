<div class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50 p-4"
     wire:click="$dispatch('close-settings-modal')"
     x-data="{
                                            showSaturday: @js($weekendSettings['show_saturday'] ?? false),
                                            showSunday: @js($weekendSettings['show_sunday'] ?? false),

                                            updateSaturday() {
                                                if (!this.showSaturday) {
                                                    this.showSunday = false;
                                                }
                                            },

                                            updateSunday() {
                                                if (this.showSunday) {
                                                    this.showSaturday = true;
                                                }
                                            },

                                            saveSettings() {
                                                $wire.set('weekendSettings.show_saturday', this.showSaturday);
                                                $wire.set('weekendSettings.show_sunday', this.showSunday);
                                                $wire.call('saveSettings');
                                            }
                                        }">

    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-hidden"
         wire:click.stop>

        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-8 py-6 text-white relative">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold">Takvim Ayarları</h2>
                    <p class="text-blue-100 mt-1">{{ $schedule?->program?->name ?? 'Takvim' }} için ayarları düzenleyin</p>
                </div>
                <button wire:click="$dispatch('close-settings-modal')"
                        class="text-white/80 hover:text-white hover:bg-white/10 p-2 rounded-full transition-all duration-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>

        @if (session()->has('error'))
            <div class="bg-red-50 border-l-4 border-red-400 p-4 m-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L10 10.414l1.707-1.707a1 1 0 011.414 1.414L11.414 12l1.707 1.707a1 1 0 01-1.414 1.414L10 13.414l-1.707 1.707a1 1 0 01-1.414-1.414L8.586 12 6.879 10.293a1 1 0 011.414-1.414L10 10.586l1.707-1.707z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <div class="p-8 max-h-[calc(90vh-140px)] overflow-y-auto">
            <div class="grid lg:grid-cols-2 gap-8">

                <div class="space-y-6">
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold text-gray-900">Zaman Konfigürasyonları</h3>
                            <p class="text-gray-500 text-sm">Kullanılacak zaman dilimlerini seçin</p>
                        </div>
                    </div>

                    @error('selectedTimeConfig')
                    <div class="text-red-600 text-sm mb-2">{{ $message }}</div>
                    @enderror

                    <div class="space-y-3 max-h-80 overflow-y-auto pr-2">
                        @foreach($timeConfigs as $config)
                            <div class="group relative">
                                <label class="flex items-center p-4 bg-gray-50 hover:bg-blue-50 rounded-xl cursor-pointer border-2 border-transparent hover:border-blue-200 {{ $selectedTimeConfig == $config->id ? 'bg-blue-50 border-blue-200' : '' }}">
                                    <input type="radio"
                                           class="sr-only peer"
                                           wire:model="selectedTimeConfig"
                                           value="{{ $config->id }}">
                                    <div class="w-5 h-5 rounded-md border-2 border-gray-300 peer-checked:bg-blue-600 peer-checked:border-blue-600 flex items-center justify-center transition-all duration-200">
                                        <svg class="w-3 h-3 text-white opacity-0 peer-checked:opacity-100" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-4 flex-1">
                                        <div class="font-medium text-gray-900">{{ $config->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $config->start_time }} - {{ $config->end_time }} - {{ $config->slot_duration }} Dakika</div>
                                    </div>
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold text-gray-900">Hafta Sonu Ayarları</h3>
                            <p class="text-gray-500 text-sm">Görünüm seçeneklerini ayarlayın</p>
                        </div>
                    </div>

                    <div class="space-y-4" >
                        <div class="flex items-center justify-between p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-100">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <span class="text-sm font-semibold text-blue-600">Ct</span>
                                </div>
                                <div>
                                    <div class="font-medium text-gray-900">Cumartesi</div>
                                    <div class="text-sm text-gray-500">Hafta sonu görünümü</div>
                                </div>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" class="sr-only peer"
                                       x-model="showSaturday"
                                       @change="updateSaturday()">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            </label>
                        </div>

                        <div class="flex items-center justify-between p-4 bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl border border-purple-100">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                                    <span class="text-sm font-semibold text-purple-600">Pz</span>
                                </div>
                                <div>
                                    <div class="font-medium text-gray-900">Pazar</div>
                                    <div class="text-sm text-gray-500">Hafta sonu görünümü</div>
                                </div>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" class="sr-only peer"
                                       x-model="showSunday"
                                       @change="updateSunday()">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-purple-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-600"></div>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-gray-50 px-8 py-6 border-t border-gray-200 flex items-center justify-between">
            <div class="text-sm text-gray-500">
                Son güncelleme: {{ now()->format('d.m.Y H:i') }}
            </div>
            <div class="flex space-x-3">
                <button wire:click="resetSettings"
                        class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium transition-colors">
                    Sıfırla
                </button>
                <button wire:click="$dispatch('close-settings-modal')"
                        class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium transition-colors">
                    İptal
                </button>
                <button @click="saveSettings()"
                        class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg hover:from-blue-700 hover:to-indigo-700 font-medium transition-all duration-200 shadow-lg hover:shadow-xl">
                    Değişiklikleri Kaydet
                </button>
            </div>
        </div>
    </div>
</div>
