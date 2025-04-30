<x-page-layout>
        <div class="fixed top-0 right-0 p-4 w-1 h-1 rounded-lg overflow-hidden z-50">
            <livewire:dashboard.scheduled-percentage />
        </div>

        <div class="grid-stack p-2 fixed">
            <div class="grid-stack-item">
                <div class="grid-stack-item-content cursor-move rounded overflow-hidden relative">
                    <livewire:dashboard.scheduled-percentage/>
                </div>
            </div>
            <div class="grid-stack-item" gs-w="12" gs-h="3">
                <div class="grid-stack-item-content cursor-move rounded overflow-hidden">
                    <livewire:dashboard.unscheduled-lessons-per-unit/>
                </div>
            </div>
            <div class="grid-stack-item overflow-hidden" gs-w="4" gs-h="3">
                <div class="grid-stack-item-content cursor-move rounded overflow-hidden ">
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
            position: relative;
            overflow: hidden;
            border-radius: 0.75rem;

        }
    </style>

</x-page-layout>
