<div class="p-4">
    @if(!empty($detailData))
        <ul>
            @foreach($detailData as $key => $value)
                <li style="font-size: 14px"><strong>{{ $key }}:</strong> {{ $value }}</li>
            @endforeach
        </ul>
    @else
    @endif
</div>
