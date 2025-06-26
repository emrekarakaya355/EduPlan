<x-plain-layout>
    <div class="p-4">
        <div class="grid-stack">


            <div class="grid-stack-item" gs-w="6" gs-h="4">
                <div class="grid-stack-item-content">
                    <livewire:dashboard.classroom-usage-per-building-apex />
                </div>
            </div>

            <div class="grid-stack-item" gs-w="6" gs-h="4">
                <div class="grid-stack-item-content">
                    <livewire:dashboard.building-based-classroom-usage />
                </div>
            </div>
            <div class="grid-stack-item" gs-w="6" gs-h="4" gs-x="3" gs-y="0" >
                <div class="grid-stack-item-content">
                    <livewire:dashboard.schedule-heat-map />
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
            margin: 10,
            maxRow: 12,
            resizable: {
                handles: 'e, se, s, sw, w'
            },
            styleInHead: true,
            draggable: {
                handle: '.grid-stack-item-content'
            },

            disableOneColumnMode: false,
            columnOpts: {
                1200: { column: 12 },
                992: { column: 8 },
                768: { column: 6 },
            }
        });
        grids.forEach(grid => grid.cellHeight());

    </script>
    @endscript

    <style>
        .grid-stack-item-content {
            /* overflow: visible !important;*/
            padding: 1rem;
            background-color: #fff;
            border-radius: 0.5rem;
            height: 100%;
            width: 100%;
            box-sizing: border-box;
        }
    </style>
</x-plain-layout>
