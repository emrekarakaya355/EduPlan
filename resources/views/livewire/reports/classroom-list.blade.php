<div>
    @if($isReportGenerated)
        <div class="flex space-x-2 mb-4">
            <button wire:click="$set('viewType', 'table')"
                    class="{{ $viewType === 'table' ? 'font-bold underline' : '' }}">
                Tablo Görünümü
            </button>
            <button wire:click="$set('viewType', 'list')"
                    class="{{ $viewType === 'list' ? 'font-bold underline' : '' }}">
                Liste Görünümü
            </button>
        </div>
        @if($viewType === 'table')
            @include('components.classrooms.partials.classroom-available-table', ['classrooms' => $classrooms,'showAvailable' => $this->filters['show_available']])
            @elseif($viewType === 'list')
            @include('components.classrooms.partials.classroom-available-list' , ['classrooms' => $classrooms,'showAvailable' => $this->filters['show_available']])
        @endif
    @else
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6 sm:px-4 bg-white border-b border-gray-200">
                <div class="text-center py-10">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Derslik Doluluk Raporu</h3>
                    <p class="mt-1 text-sm text-gray-500">
                        Raporu görüntülemek için sol taraftaki filtrelerden seçim yaparak "Raporu Oluştur" butonuna tıklayın.
                    </p>
                </div>
            </div>
        </div>
    @endif
</div>
