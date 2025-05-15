<?php

namespace App\Services\Pdf\Strategies;

use Illuminate\Support\Facades\View;

class ScheduleChartPdf extends PdfStrategy {

    public function getView(): string
    {
        return 'pdfs.schedule-chart';
    }

    public function getDefaultOptions(): array
    {
        return [
            'paper' => 'a4',
            'orientation' => 'landscape',
            'attachment'=>false
        ];
    }

    public function generateFileName(array $data): string
    {
        $period = $data['period'] ?? 'aylik';
        return 'Haftalık-ders-takvimi-' . $period . '-' . date('Y-m-d') . '.pdf';
    }
}
