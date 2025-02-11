<div class="grid grid-cols-8 gap-1 p-4">
    <!-- Sol üst boş kutu -->
    <div class="p-2"></div>

    <!-- Günler -->
    <div class="text-center font-bold p-2 bg-gray-800 text-white">Pazartesi</div>
    <div class="text-center font-bold p-2 bg-gray-800 text-white">Salı</div>
    <div class="text-center font-bold p-2 bg-gray-800 text-white">Çarşamba</div>
    <div class="text-center font-bold p-2 bg-gray-800 text-white">Perşembe</div>
    <div class="text-center font-bold p-2 bg-gray-800 text-white">Cuma</div>
    <div class="text-center font-bold p-2 bg-gray-800 text-white">Cumartesi</div>
    <div class="text-center font-bold p-2 bg-gray-800 text-white">Pazar</div>

    <!-- Saatler ve çizelge -->
    @foreach(range(8, 20) as $hour)
        <div class="text-center font-bold p-2 bg-gray-700 text-white">{{ $hour }}:00</div>
        @foreach(range(1, 7) as $day)
            <div class="border p-4 min-h-[50px] bg-gray-100" data-day="{{ $day }}" data-hour="{{ $hour }}"></div>
        @endforeach
    @endforeach
</div>
