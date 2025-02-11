<div class="p-4">
    <h2 class="text-xl font-bold mb-4">Dersler</h2>
    @if($courses->isNotEmpty())
        <div class="mt-4">{{$courses->links()}}</div>

        <table wire:model.live="courses">
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
            @foreach($courses as $courseClass)
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
    @else
        <p class="text-gray-500">Hiç ders bulunamadı.</p>
    @endif
</div>
