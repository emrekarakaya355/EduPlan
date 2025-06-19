<div id="instructorConstraintsModal" class="fixed constraints-modal inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50 p-4"
     >
    <div class="modal-backdrop" ></div>
    <div class="modal-content">

        <!-- Modal Body -->
        <div class="modal-body">

            <!-- Flash Messages -->
            @if (session()->has('message'))
                <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    {{ session('message') }}
                </div>
            @endif
            <!-- Haftalık Takvim Görünümü -->
            <div class="bg-gray-50 rounded-xl p-6">
                <div class="flex align-middle justify-between p-4">
                    <div> </div>
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900">{{$this->instructor?->name ? $this->instructor?->name : 'Eğitmen'}} Zaman Kısıtlamaları</h3>
                        <p class="text-sm text-gray-600 mt-1">Eğitmenin müsait olmadığı gün ve saatleri belirleyin</p>
                    </div>
                    <button onclick="Livewire.dispatch('close-instructor-constraints-modal')" class="modal-close">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <div class="grid grid-cols-7 gap-3">
                    @foreach($days as $dayNumber => $dayName)
                        <div class="bg-white border border-gray-200 rounded-lg p-4 min-h-[320px] shadow-sm hover:shadow-md transition-shadow">
                            <!-- Gün Başlığı -->
                            <div class="flex justify-between items-center mb-4 pb-2 border-b border-gray-100">
                                <h4 class="font-semibold text-gray-800 text-sm">{{ $dayName }}</h4>
                                <button
                                    wire:click="openForm({{ $dayNumber }})"
                                    class="text-blue-600 hover:text-blue-800 hover:bg-blue-50 p-1 rounded-md transition-colors"
                                    title="Kısıtlama Ekle"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                    </svg>
                                </button>
                            </div>

                            <!-- Gün için Kısıtlamalar -->
                            <div class="space-y-2">
                                @foreach($this->getConstraintsByDay($dayNumber) as $constraint)
                                    <div class="bg-red-50 border border-red-200 rounded-lg p-3 text-sm hover:bg-red-100 transition-colors">
                                        <div class="flex justify-between items-start">
                                            <div class="flex-1">
                                                <div class="font-medium text-red-900 flex items-center">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                                    </svg>
                                                    {{ $constraint->start_time->format('H:i') }} - {{$constraint->end_time->format('H:i') }}
                                                </div>
                                                @if($constraint->note)
                                                    <div class="text-red-700 text-xs mt-2 p-2 bg-red-100 rounded">
                                                        {{ $constraint->note }}
                                                    </div>
                                                @endif
                                                <div class="text-red-700 text-xs mt-2 p-2 bg-red-100 rounded">
                                                    {{ $constraint?->createdBy?->name }}
                                                </div>
                                            </div>
                                            <div class="flex space-x-1 ml-2">
                                                <button
                                                    wire:click="editConstraint({{ $constraint->id }})"
                                                    class="text-blue-600 hover:text-blue-800 hover:bg-blue-100 p-1 rounded"
                                                    title="Düzenle"
                                                >
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                    </svg>
                                                </button>
                                                <button
                                                    wire:click="deleteConstraint({{ $constraint->id }})"
                                                    wire:confirm="Bu kısıtlamayı silmek istediğinizden emin misiniz?"
                                                    class="text-red-600 hover:text-red-800 hover:bg-red-100 p-1 rounded"
                                                    title="Sil"
                                                >
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                                @if($this->getConstraintsByDay($dayNumber)->isEmpty())
                                    <div class="text-center text-gray-400 text-xs py-8">
                                        <svg class="w-8 h-8 mx-auto mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        Kısıtlama yok
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Kısıtlama Ekleme/Düzenleme Formu Modal -->
        @if($showForm)
            <div class="fixed inset-0 bg-gray-900 bg-opacity-60 overflow-y-auto h-full w-full z-50 backdrop-blur-sm">
                <div class="relative top-20 mx-auto p-5 border-0 w-11/12 md:w-1/2 lg:w-1/3 shadow-2xl rounded-xl bg-white">
                    <div class="flex justify-between items-center mb-6 pb-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">
                            {{ $editingConstraintId ? 'Kısıtlama Düzenle' : 'Yeni Kısıtlama Ekle' }}
                        </h3>
                        <button wire:click="closeForm" class="text-gray-400 hover:text-gray-600 hover:bg-gray-100 p-2 rounded-lg transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <form wire:submit.prevent="saveConstraint" class="space-y-5">
                        <!-- Gün Seçimi -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Gün</label>
                            <select wire:model="selectedDay" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Gün seçiniz...</option>
                                @foreach($days as $dayNumber => $dayName)
                                    <option value="{{ $dayNumber }}">{{ $dayName }}</option>
                                @endforeach
                            </select>
                            @error('selectedDay') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Zaman Aralığı -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Başlangıç Saati</label>
                                <select wire:model="startTime" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">Saat seçiniz...</option>
                                    @foreach($timeSlots as $slot)
                                        <option value="{{ $slot }}">{{ $slot }}</option>
                                    @endforeach
                                </select>
                                @error('startTime') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Bitiş Saati</label>
                                <select wire:model="endTime" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">Saat seçiniz...</option>
                                    @foreach($timeSlots as $slot)
                                        <option value="{{ $slot }}">{{ $slot }}</option>
                                    @endforeach
                                </select>
                                @error('endTime') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Not -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Not</label>
                            <textarea
                                wire:model="note"
                                required
                                rows="3"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"
                                placeholder="Bu kısıtlama hakkında not..."
                            ></textarea>
                            @error('note') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Çakışma Hatası -->
                        @error('timeConflict')
                        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            {{ $message }}
                        </div>
                        @enderror

                        <!-- Form Butonları -->
                        <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                            <button
                                type="button"
                                wire:click="closeForm"
                                class="px-6 py-3 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 transition-colors"
                            >
                                İptal
                            </button>
                            <button
                                type="submit"
                                class="px-6 py-3 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors"
                            >
                                {{ $editingConstraintId ? 'Güncelle' : 'Kaydet' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    </div>
</div>

@push('scripts')
    <script>
        // Modal dışına tıklandığında kapatma
        document.addEventListener('click', function(event) {
            if (event.target.classList.contains('bg-gray-900')) {
                @this.closeForm();
            }
        });
    </script>
@endpush

@push('styles')
    <style>
        /* Modal Styles */
        .constraints-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1050;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            box-sizing: border-box;
        }

        .modal-backdrop {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(4px);
        }

        .modal-content {
            position: relative;
            background: white;
            border-radius: 16px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            width: 95%;
            max-width: 1400px;
            max-height: 95vh;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 24px 32px;
            border-bottom: 1px solid #e5e7eb;
            background: white;
        }

        .modal-close {
            background: rgba(255, 255, 255, 0.8);
            border: 1px solid #e5e7eb;
            font-size: 16px;
            cursor: pointer;
            color: #6b7280;
            padding: 8px;
            border-radius: 8px;
            transition: all 0.2s ease;
            backdrop-filter: blur(8px);
        }

        .modal-close:hover {
            background-color: #f3f4f6;
            color: #374151;
            transform: scale(1.05);
        }

        .modal-body {
            padding: 32px;
            max-height: calc(95vh - 120px);
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: #cbd5e1 #f1f5f9;
        }

        .modal-body::-webkit-scrollbar {
            width: 6px;
        }

        .modal-body::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 3px;
        }

        .modal-body::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }

        .modal-body::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .modal-content {
                width: 98%;
                max-height: 98vh;
            }

            .modal-header {
                padding: 20px 24px;
            }

            .modal-body {
                padding: 24px;
            }

            .grid-cols-7 {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 8px;
            }
        }

        @media (max-width: 640px) {
            .grid-cols-7 {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush
