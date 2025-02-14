<div>
    <div >
        <div>
            <h2>
                <div class="overflow-y-auto max-h-2" style="max-height: 700px;">
                    <table>
                        <thead>
                        <tr>
                            <th>ders kodu</th>
                            <th>ders adı</th>
                            <th>adı</th>

                        </tr>
                        </thead>
                        <tbody>
                        @foreach($courses as $courseClass)
                            <tr wire:key="course-class-{{ $courseClass->id }}" data-id="{{$courseClass->id}}"  class="" draggable="true" ondragstart="drag(event)"  style="border: 1px solid #ddd; padding: 10px; ">
                                <td>{{ $courseClass->course->name }}</td>
                                <td>{{ $courseClass->course->code }}</td>
                                <td>{{ $courseClass->instructor?->adi }}</td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                </div>
            </h2>
        </div>
    </div></div>
