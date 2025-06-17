<div class="{{$showForm}} ? '' max-w-6xl mx-auto p-6">
    <div class="bg-white rounded-lg shadow-lg">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold text-gray-800">Zaman Ayarları</h2>
                <button wire:click="showCreateForm"
                        class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-plus mr-2"></i>Yeni Ekle
                </button>
            </div>
        </div>

        <!-- Flash Messages -->
        @if (session()->has('message'))
            <div class="mx-6 mt-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                {{ session('message') }}
            </div>
        @endif

        @if (session()->has('error'))
            <div class="mx-6 mt-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        <!-- Form -->
        @if ($showForm)
            <div class="p-6 border-b border-gray-200 bg-gray-50">
                <h3 class="text-lg font-medium text-gray-800 mb-4">
                    {{ $editingId ? 'Zaman Ayarını Düzenle' : 'Yeni Zaman Ayarı' }}
                </h3>

                <form wire:submit="save" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Name -->
                    <div class="md:col-span-2">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                            Ayar Adı
                        </label>
                        <input type="text"
                               wire:model="name"
                               id="name"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Örn: Normal Çalışma Saatleri">
                        @error('name')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Start Time -->
                    <div>
                        <label for="start_time" class="block text-sm font-medium text-gray-700 mb-1">
                            Başlangıç Saati
                        </label>
                        <input type="time"
                               wire:model="start_time"
                               id="start_time"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('start_time')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- End Time -->
                    <div>
                        <label for="end_time" class="block text-sm font-medium text-gray-700 mb-1">
                            Bitiş Saati
                        </label>
                        <input type="time"
                               wire:model="end_time"
                               id="end_time"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('end_time')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Slot Duration -->
                    <div>
                        <label for="slot_duration" class="block text-sm font-medium text-gray-700 mb-1">
                            Slot Süresi (dakika)
                        </label>
                        <input type="number"
                               wire:model="slot_duration"
                               id="slot_duration"
                               min="5"
                               max="240"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('slot_duration')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Break Duration -->
                    <div>
                        <label for="break_duration" class="block text-sm font-medium text-gray-700 mb-1">
                            Mola Süresi (dakika)
                        </label>
                        <input type="number"
                               wire:model="break_duration"
                               id="break_duration"
                               min="0"
                               max="120"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @error('break_duration')
                        <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Form Buttons -->
                    <div class="md:col-span-2 flex gap-3 pt-4">
                        <button type="submit"
                                class="bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded-lg transition-colors">
                            <i class="fas fa-save mr-2"></i>Kaydet
                        </button>
                        <button type="button"
                                wire:click="cancel"
                                class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition-colors">
                            <i class="fas fa-times mr-2"></i>İptal
                        </button>
                    </div>
                </form>
            </div>
        @endif

        <!-- Table -->
        <div class="p-6">
            @if ($scheduleConfigs->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full table-auto border-collapse">
                        <thead>
                        <tr class="bg-gray-50">
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-700 border-b">
                                Ayar Adı
                            </th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-700 border-b">
                                Başlangıç
                            </th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-700 border-b">
                                Bitiş
                            </th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-700 border-b">
                                Slot Süresi
                            </th>
                            <th class="px-4 py-3 text-left text-sm font-medium text-gray-700 border-b">
                                Mola Süresi
                            </th>
                            <th class="px-4 py-3 text-center text-sm font-medium text-gray-700 border-b">
                                İşlemler
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($scheduleConfigs as $config)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-4 py-3 border-b">
                                    <span class="font-medium text-gray-900">{{ $config->name }}</span>
                                </td>
                                <td class="px-4 py-3 border-b text-gray-700">
                                    {{ \Carbon\Carbon::parse($config->start_time)->format('H:i') }}
                                </td>
                                <td class="px-4 py-3 border-b text-gray-700">
                                    {{ \Carbon\Carbon::parse($config->end_time)->format('H:i') }}
                                </td>
                                <td class="px-4 py-3 border-b text-gray-700">
                                    {{ $config->slot_duration }} dk
                                </td>
                                <td class="px-4 py-3 border-b text-gray-700">
                                    {{ $config->break_duration }} dk
                                </td>
                                <td class="px-4 py-3 border-b text-center">
                                    <div class="flex justify-center gap-2">
                                        <button wire:click="edit({{ $config->id }})"
                                                class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-sm transition-colors">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button wire:click="delete({{ $config->id }})"
                                                wire:confirm="Bu zaman ayarını silmek istediğinizden emin misiniz?"
                                                class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm transition-colors">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-8">
                    <div class="text-gray-400 mb-4">
                        <i class="fas fa-clock text-4xl"></i>
                    </div>
                    <p class="text-gray-500">Henüz zaman ayarı bulunmuyor.</p>
                    <button wire:click="showCreateForm"
                            class="mt-4 bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition-colors">
                        İlk Zaman Ayarını Oluştur
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>
