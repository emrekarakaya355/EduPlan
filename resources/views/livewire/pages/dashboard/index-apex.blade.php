<x-page-layout>
    <div class="w-full min-h-screen p-4">
        <div class="grid-stack w-full">
            <div class="grid-stack-item p-4" gs-w="5" gs-h="4">
                <div class="grid-stack-item-content h-full w-full ">
                    <livewire:dashboard.schedule-heat-map />
                </div>
            </div>
            <div class="grid-stack-item" gs-w="5" gs-h="4">
                <div class="grid-stack-item-content h-full w-full">
                    <livewire:dashboard.classroom-usage-per-building-apex />
                </div>
            </div>
            <div class="grid-stack-item" gs-w="5" gs-h="4">
                <div class="grid-stack-item-content h-full w-full">
                    <livewire:dashboard.building-based-classroom-usage />
                </div>
            </div>



        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
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

            window.addEventListener('load', function() {
                grids.forEach(grid => grid.cellHeight());
            });
        });
    </script>

    <style>
        .grid-stack {
            width: 100% !important;
        }
        .grid-stack-item {
        }
        .grid-stack-item-content {
            overflow-x: visible !important;
            overflow-y: visible !important;
        }
    </style>
</x-page-layout>
