<div>
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
    <table class="min-w-full table-auto border border-gray-300 text-sm">
        <thead class="bg-gray-100">
        <tr>
            <th class="border px-2 py-1">Kampüs</th>
            <th class="border px-2 py-1">Bina</th>
            <th class="border px-2 py-1">Derslik</th>
            <th class="border px-2 py-1">Derslik Kapasitesi</th>
            <th class="border px-2 py-1">Sınav Kapasitesi</th>
            <th class="border px-2 py-1">Gün</th>
            <th class="border px-2 py-1">Saat</th>
            <th class="border px-2 py-1">Durum</th>
        </tr>
        </thead>
        <tbody>
        @foreach($classrooms as $classroom)
            @php
                $availability = $classroom->dailyAvailability($this->filters['selected_days'],$this->filters['start_time'],$this->filters['end_time'] , $this->filters['show_available']);
                @endphp
            @foreach($availability as  $day => $times)
                @foreach($times as $time => $status)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-4 py-2">{{ $classroom->building->campus->name }}</td>
                            <td class="px-4 py-2">{{ $classroom->building->name }}</td>
                            <td class="px-4 py-2">{{ $classroom->name }}</td>
                            <td class="px-4 py-2">{{ $classroom->class_capacity }}</td>
                            <td class="px-4 py-2">{{ $classroom->exam_capacity }}</td>
                            <td class="px-4 py-2">{{ $day }}</td>
                            <td class="px-4 py-2">{{ $time }}</td>
                            <td class="px-4 py-2">
                                <span class="font-semibold text-sm
                                    @if($status == 'boş') text-green-500
                                    @elseif($status == 'dolu') text-red-500
                                    @else text-gray-500 @endif">
                                    {{ $status }}
                                </span>
                            </td>
                        </tr>
                @endforeach
            @endforeach
        @endforeach
        </tbody>
    </table>

</div>
