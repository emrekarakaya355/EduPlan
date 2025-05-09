<x-plain-layout>
    <div class="p-4">
        <div class="grid-stack ">
            <div class="grid-stack-item " gs-w="5" gs-h="4">
                <div class="grid-stack-item-content " style="width: 800px;">
                    <livewire:dashboard.schedule-heat-map />
                </div>
            </div>
            <div class="grid-stack-item" gs-w="5" gs-h="4" >
                <div class="grid-stack-item-content h-full w-full" style="width: 900px; margin-left: 50px">

                    <livewire:dashboard.classroom-usage-per-building-apex />
                </div>
            </div>
            <div class="grid-stack-item w-1/2" gs-w="5" gs-h="4" >
                <div class="grid-stack-item-content " style="width: 900px;">
                    <livewire:dashboard.building-based-classroom-usage />
                </div>
            </div>

        </div>
    </div>

    @script
    <script>
            const grids = GridStack.initAll({
                column: 12,
                float: true,
                cellHeight: 'auto',
                margin: 5,
                maxRow: 12,

                resizable: {
                    handles: 'e, se, s, sw, w'
                },
                styleInHead: true,
                draggable: {
                    handle: '.grid-stack-item-content'
                }
            });
            grids.forEach(grid => grid.cellHeight());
    </script>
    @endscript
    <style>
        .grid-stack {

        }
        .grid-stack-item {

        }
        .grid-stack-item-content {
            overflow: visible !important;
        }
    </style>
</x-plain-layout>
