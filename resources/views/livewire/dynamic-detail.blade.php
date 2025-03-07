<div class="p-4 whitespace-nowrap" >
    @if(!empty($detailData))
        <ul class=" overflow-x-scroll overflow-y-auto">
            @foreach($detailData as $key => $value)
                <li style="font-size: 14px"><strong>{{ $key }}:</strong> {{ $value }}</li>
            @endforeach
        </ul>
    @else
    @endif
</div>
