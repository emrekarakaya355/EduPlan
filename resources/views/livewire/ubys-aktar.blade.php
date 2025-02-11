<div>
    <button type="button" wire:click="fetchData" class="px-4 py-2 bg-blue-500 rounded-lg hover:bg-blue-600">
        Birimleri Al
    </button>
    <form wire:submit="changeSelectedBirim">
        <div class="">
                <div >
                    <!-- Birim Seçimi -->
                    <label for="birim">Birim:</label>
                    <select id="birim" wire:model.live="selectedBirim">
                        <option value="">Bir Birim Seçin</option>
                        @foreach($birims as $birim)
                            <option value="{{ $birim->id }}">{{ $birim->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div >
                    <!-- Bölüm Seçimi -->
                        <label for="bolum">Bölüm:</label>
                        <select id="bolum" wire:model.live="selectedBolum">
                            <option value="">Bir Bölüm Seçiniz</option>
                            @if($selectedBirim)
                                @foreach($birims->firstWhere('id', $selectedBirim)->bolums as $bolum)
                                    <option value="{{ $bolum->id }}">{{ $bolum->name }}</option>
                                @endforeach
                            @endif

                        </select>
                </div>
            <div >
                <!-- Program Seçimi -->
                <label for="program">Program:</label>
                <select id="program" wire:model.live="selectedProgram">
                    <option value="">Bir Program Seçin</option>
                    @if($selectedBolum)
                        @php
                            $selectedBolumObject = $birims
                                ->firstWhere('id', $selectedBirim)
                                ?->bolums
                                ->firstWhere('id', $selectedBolum);
                        @endphp
                        @if($selectedBolumObject)
                            @foreach($selectedBolumObject->programs as $program)
                                <option value="{{ $program->id }}">{{ $program->name }}   {{ $program->year }}   {{ $program->semester }}</option>
                            @endforeach
                        @endif
                    @endif
                </select>
            </div>
            <div class="p-8">
                @if(count($courseClasses) > 0)
                    <p>Toplam {{ count($courseClasses) }} kurs sınıfı bulundu.</p>
                @else
                    <p></p>
                @endif
                <table wire:model.live="courseClasses">
                    <thead >
                    <tr>
                        <th>Course Code</th>
                        <th>Course Name</th>
                        <th>Instructor Name</th>
                        <th>Instructor Email</th>
                        <th>Grade</th>
                        <th>Branch</th>
                        <th>Saat</th>
                        <th>Kontenjan</th>
                        <th>Yıl</th>
                        <th>Dönem</th>
                        <th>ubys-course-id</th>
                        <th>ubys-class-id</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($courseClasses as $courseClass)
                        <tr>
                            <td>{{ $courseClass->course->code }}</td>
                            <td>{{ $courseClass->course->name }}</td>
                            <td>{{ $courseClass->instructorName }} {{ $courseClass->instructorSurname }}</td>
                            <td>{{ $courseClass->instructorEmail }}</td>
                            <td>{{ $courseClass->grade }}</td>
                            <td>{{ $courseClass->branch }}</td>
                            <td>{{ $courseClass->duration }}</td>
                            <td>{{ $courseClass->quota }}</td>
                            <td>{{ $courseClass->course->year }}</td>
                            <td>{{ $courseClass->course->semester }}</td>
                            <td>{{ $courseClass->external_id }}</td>
                            <td>{{ $courseClass->course->external_id }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            @if(false)
            <div class="p-2">
                <!-- Birim Seçimi -->
                <label for="orphanedBirims">bolum olmayan Birimler:</label>
                <select id="orphanedBirims" wire:model="orphanedBirims">
                    <option value="">Bir Birim Seçin</option>
                    @foreach($orphanedBirims as $birim)
                        <option value="{{ $birim->id }}">{{ $birim->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="p-2">
                <!-- Birim Seçimi -->
                <label for="orphanedBolums">Program olmayan Bolumler:</label>
                <select id="orphanedBolums" wire:model="orphanedBolums">
                    <option value="">Bir Birim Seçin</option>
                    @foreach($orphanedBolums as $birim)
                        <option value="{{ $birim->id }}">{{  $birim->id .' '.$birim->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="p-2">
                <label for="orphanedBolums">Program Olmayan Bölümler:</label>
                <table class="w-full border-collapse border border-gray-300 mt-2">
                    <thead>
                    <tr class="bg-gray-100">
                        <th class="border border-gray-300 px-4 py-2">ID</th>
                        <th class="border border-gray-300 px-4 py-2">Bölüm Adı</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($orphanedBolums as $birim)
                        <tr>
                            <td class="border border-gray-300 px-4 py-2">{{ $birim->external_id }}</td>
                            <td class="border border-gray-300 px-4 py-2">{{ $birim->name }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </form>
</div>
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
