<?php

namespace App\Services\Pdf\Strategies;

class TablePdf extends pdfStrategy
{
    public function getView(): string
    {
        return 'pdfs.table-pdf';
    }

    public function getDefaultOptions(): array
    {
        return [
            'paper' => 'a4',
            'orientation' => 'portrait',
        ];
    }

    public function generateFileName(array $data): string
    {
        $period = $data['period'] ?? 'aylik';
        return 'Haftalık-ders-listesi-' . $period . '-' . date('Y-m-d') . '.pdf';
    }
}
