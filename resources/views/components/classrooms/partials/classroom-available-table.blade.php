<div>
    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
        <div class="flex items-center justify-between">

            <div class="mt-4">
                {{ $classrooms->links() }}
            </div>
            <div class="flex items-center">
                <span class="mr-2">Sayfa başına gösterim:</span>
                <select wire:model.live="perPage" class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm">
                    <option>10</option>
                    <option>25</option>
                    <option>50</option>
                    <option>100</option>
                    <option>2</option>
                </select>
            </div>
        </div>

        @forelse($classrooms as $classroomItem)
            <div class="mb-8">
                <h4 class="text-md font-semibold text-gray-700 mb-2">
                    {{ $classroomItem->building->campus->name }} / {{ $classroomItem->building->name }} / {{ $classroomItem->name }} ({{ $classroomItem->type }})
                </h4>

                <table class="min-w-full table-auto border border-gray-300 text-sm">
                    <thead class="bg-gray-100">
                    <tr>
                        <th class="border px-2 py-1">Saat</th>
                        @foreach(\App\Enums\DayOfWeek::cases() as $day)
                            <th class="border px-2 py-1">{{ $day->getLabel() }}</th>
                        @endforeach
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($classroomItem->weekly_availability as $time => $days)
                            <tr>
                                <td class="border text-center">{{ $time }}</td>
                                @foreach($days as $status)
                                    <td class="border px-2 py-1 text-center">
                                        @if($status === 'boş')
                                            <span class="text-green-600 font-medium">Boş</span>
                                        @else
                                            <span class="text-red-600 font-medium">Dolu</span>
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @empty
            <p class="text-gray-500">Uygun derslik bulunamadı.</p>
        @endforelse

    </div>
</div>
