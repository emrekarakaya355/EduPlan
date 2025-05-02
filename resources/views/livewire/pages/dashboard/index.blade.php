<x-page-layout>
        <div class="fixed top-0 right-0 p-4 w-1 h-1 rounded-lg overflow-hidden z-50">
            <livewire:dashboard.scheduled-percentage />
        </div>

        <div class="grid-stack p-2">
            <div class="grid-stack-item">
                <div class="grid-stack-item-content cursor-move overflow-y-visible rounded " gs-w="6" gs-h="3">
                    <livewire:dashboard.scheduled-percentage/>
                </div>
            </div>
            <div class="grid-stack-item">
                <div class="grid-stack-item-content" gs-w="6" gs-h="3" >
                    <livewire:dashboard.buildinged-based-classroom-usage/>
                </div>
            </div>
            <div class="grid-stack-item" gs-w="6" gs-h="3">
                <div class="grid-stack-item-content cursor-move rounded ">
                    <livewire:dashboard.unscheduled-lessons-per-unit/>
                </div>
            </div>
            <div class="grid-stack-item " gs-w="6" gs-h="3">
                <div class="grid-stack-item-content cursor-move rounded">
                    <livewire:dashboard.classroom-usage-per-building/>
                </div>
            </div>


        </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            console.log('GridStack Başlatıldı');
            const grid = GridStack.init({
                column: 12,
                float: true,
                cellHeight: 'auto',
                margin: 5,
                resizable: {
                    handles: 'e, se, s, sw, w'
                },
            });

            console.log('GridStack Başlatıldı', grid);
        });
    </script>

    <style>
        .grid-stack {
            width: 100%;
            height:100%;
        }

        .grid-stack-item-content {
            width: 100%;
            height: 100%;
            overflow-x: visible !important;
            overflow-y: visible !important;


        }
    </style>

</x-page-layout>
