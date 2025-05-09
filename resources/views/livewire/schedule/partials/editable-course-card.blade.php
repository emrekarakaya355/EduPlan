<div class="cell border-l rounded"
     style="background-color: {{ $class['color'] ?? '#FFFFFF' }};"
>
    <div class="text-sm name" contenteditable="true">{{ $class['class_code'] }}</div>
    <div class="name" contenteditable="true">{{ $class['class_name'] }}</div>
    <div class="font-bold abbreviation"  contenteditable="true">{{$viewMode === 'instructor' ?  $class['program_name'] :$class['instructor_name'] }}</div>
    <div contenteditable="true">{{ $class['classrom_name'] ?? '(Derslik Sonra Belirtilecek)' }}</div>
    <div contenteditable="true" style="font-size: xx-small">{{ $class['building_name'] ?? '' }}</div>
</div>
<style>
    .cell {
        height: 65px;
        overflow: visible;
        display: flex;
        text-align: center;
        flex-direction: column;
        justify-content: center;
    }
    .cell div {
        font-size: 9px;
        line-height: 1.1;
        overflow: visible;
        text-overflow: ellipsis;
        word-break: break-word;
    }
</style>
<script>

</script>
