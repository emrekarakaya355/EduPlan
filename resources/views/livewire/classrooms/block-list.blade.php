<div class="p-4">
    <h3 class="font-bold mb-2">Derslikler</h3>
    <div class="grid grid-cols-3 gap-4">
        @foreach($classrooms as $classroom)
            <div class="bg-gray-200 p-4 rounded shadow">
                <h4 class="font-bold">{{ $classroom->name }}</h4>
                <p>Kapasite: {{ $classroom->capacity }} kişi</p>
            </div>
        @endforeach
    </div>
</div>
