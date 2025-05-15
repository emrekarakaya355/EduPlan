<?php

namespace App\Services\Pdf;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\Pdf\Strategies\PdfStrategy;

class PdfService
{
    public function download(PdfStrategy $strategy, array $data = [])
    {
        $pdf = Pdf::loadView($strategy->getView(), $data)
            ->setPaper(
                $strategy->getDefaultOptions()['paper'] ?? 'a4',
                $strategy->getDefaultOptions()['orientation'] ?? 'portrait'
            );

        return response()->stream(function () use ($pdf) {
            echo $pdf->output();
        }, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $strategy->generateFileName($data) . '"',
        ]);
    }

    public function stream(PdfStrategy $strategy, array $data = [])
    {
        $pdf = Pdf::loadView($strategy->getView(), $data)
            ->setPaper(
                $strategy->getDefaultOptions()['paper'] ?? 'a4',
                $strategy->getDefaultOptions()['orientation'] ?? 'portrait'
            );

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        },$strategy->generateFileName($data));
    }
}
