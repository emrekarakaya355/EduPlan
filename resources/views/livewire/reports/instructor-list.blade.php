<div class="w-full">
    <div class="flex flex-wrap">
        <div class="w-full">
            <div class="bg-white rounded-lg shadow-md">
                <div class="p-6">
                    <div class="mb-3">
                        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-2">
                            <div class="mb-2 md:mb-0">
                                <p class="text-gray-500">
                                    Toplam {{ $constraints->total() }} kayıt bulundu
                                </p>
                            </div>
                            <div class="flex items-center">
                                <select wire:model="perPage" class="border border-gray-300 rounded px-3 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="10">10</option>
                                    <option value="15">15</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                </select>
                                <span class="text-gray-500 ml-2">kayıt göster</span>
                            </div>
                        </div>
                    </div>

                    {{-- Table --}}
                    <div class="overflow-x-auto">
                        <table class="min-w-full border border-gray-200">
                            <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left">
                                    <button type="button"
                                            class="text-gray-700 font-semibold hover:text-gray-900 focus:outline-none flex items-center"
                                            wire:click="sortBy('instructor_id')">
                                        Instructor
                                        @if($sortBy === 'instructor_id')
                                            <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ml-1"></i>
                                        @endif
                                    </button>
                                </th>
                                <th class="px-4 py-3 text-left">
                                    <button type="button"
                                            class="text-gray-700 font-semibold hover:text-gray-900 focus:outline-none flex items-center"
                                            wire:click="sortBy('day_of_week')">
                                        Gün
                                        @if($sortBy === 'day_of_week')
                                            <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ml-1"></i>
                                        @endif
                                    </button>
                                </th>
                                <th class="px-4 py-3 text-left">
                                    <button type="button"
                                            class="text-gray-700 font-semibold hover:text-gray-900 focus:outline-none flex items-center"
                                            wire:click="sortBy('start_time')">
                                        Başlangıç
                                        @if($sortBy === 'start_time')
                                            <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ml-1"></i>
                                        @endif
                                    </button>
                                </th>
                                <th class="px-4 py-3 text-left">
                                    <button type="button"
                                            class="text-gray-700 font-semibold hover:text-gray-900 focus:outline-none flex items-center"
                                            wire:click="sortBy('end_time')">
                                        Bitiş
                                        @if($sortBy === 'end_time')
                                            <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ml-1"></i>
                                        @endif
                                    </button>
                                </th>
                                <th class="px-4 py-3 text-left">Not</th>
                                <th class="px-4 py-3 text-left">
                                    <button type="button"
                                            class="text-gray-700 font-semibold hover:text-gray-900 focus:outline-none flex items-center"
                                            wire:click="sortBy('created_by')">
                                        Oluşturan
                                        @if($sortBy === 'created_by')
                                            <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ml-1"></i>
                                        @endif
                                    </button>
                                </th>
                                <th class="px-4 py-3 text-left">
                                    <button type="button"
                                            class="text-gray-700 font-semibold hover:text-gray-900 focus:outline-none flex items-center"
                                            wire:click="sortBy('created_at')">
                                        Oluşturma Tarihi
                                        @if($sortBy === 'created_at')
                                            <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ml-1"></i>
                                        @endif
                                    </button>
                                </th>
                                <th class="px-4 py-3 text-left">Güncelleyen</th>
                            </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($constraints as $constraint)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-4">
                                        <div>
                                            <div class="font-semibold text-gray-900">{{ $constraint->instructor->name ?? 'N/A' }}</div>
                                            @if($constraint->instructor)
                                                <div class="text-sm text-gray-500">{{ $constraint->instructor->email }}</div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-4 py-4">
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold bg-blue-100 text-blue-800 rounded-full">
                                                {{ $this->getDayName($constraint->day_of_week) }}
                                            </span>
                                    </td>
                                    <td class="px-4 py-4">
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold bg-green-100 text-green-800 rounded-full">
                                                {{ \Carbon\Carbon::parse($constraint->start_time)->format('H:i') }}
                                            </span>
                                    </td>
                                    <td class="px-4 py-4">
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold bg-red-100 text-red-800 rounded-full">
                                                {{ \Carbon\Carbon::parse($constraint->end_time)->format('H:i') }}
                                            </span>
                                    </td>
                                    <td class="px-4 py-4">
                                        @if($constraint->note)
                                            <div class="text-gray-600">{{ Str::limit($constraint->note, 50) }}</div>
                                            @if(strlen($constraint->note) > 50)
                                                <div class="mt-1">
                                                    <button class="text-blue-600 hover:text-blue-800 text-sm"
                                                            title="{{ $constraint->note }}">
                                                        Devamını gör...
                                                    </button>
                                                </div>
                                            @endif
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4">
                                        @if($constraint->createdBy)
                                            <div>
                                                <div class="font-semibold text-gray-900">{{ $constraint->createdBy->name }}</div>
                                                <div class="text-sm text-gray-500">{{ $constraint->createdBy->email }}</div>
                                            </div>
                                        @else
                                            <span class="text-gray-400">Bilinmiyor</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4">
                                        <div>
                                            <div class="text-gray-900">{{ $constraint->created_at->format('d.m.Y') }}</div>
                                            <div class="text-sm text-gray-500">{{ $constraint->created_at->format('H:i') }}</div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4">
                                        @if($constraint->updatedBy)
                                            <div>
                                                <div class="font-semibold text-gray-900">{{ $constraint->updatedBy->name }}</div>
                                                <div class="text-sm text-gray-500">
                                                    {{ $constraint->updated_at->format('d.m.Y H:i') }}
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-4 py-12 text-center">
                                        <div class="text-gray-400">
                                            <i class="fas fa-search text-4xl mb-4"></i>
                                            <h5 class="text-lg font-semibold text-gray-600 mb-2">Kayıt bulunamadı</h5>
                                            <p class="text-gray-500">Arama kriterlerinizi değiştirmeyi deneyin.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    <div class="flex flex-col sm:flex-row justify-between items-center mt-4 space-y-2 sm:space-y-0">
                        <div class="text-gray-500 text-sm">
                            {{ $constraints->firstItem() }}-{{ $constraints->lastItem() }}
                            / {{ $constraints->total() }} kayıt gösteriliyor
                        </div>
                        <div>
                            {{ $constraints->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        // Tooltip işlevselliği için
        document.addEventListener('DOMContentLoaded', function() {
            // Tailwind ile tooltip işlevselliği ekleyebilirsiniz
            // veya Alpine.js kullanabilirsiniz
        });
    </script>
@endpush
