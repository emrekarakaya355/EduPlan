<?php

namespace App\Services\Pdf\Strategies;

use App\Contracts\PdfStrategyInterface;

abstract class PdfStrategy implements PdfStrategyInterface
{
    protected $styles = [
        'default' => [],
        'modern' => [
            'margin_top' => 15,
            'margin_bottom' => 15,
            'margin_left' => 10,
            'margin_right' => 10,
            'footer_html' => 'pdfs.partials.modern-footer'
        ],
        'minimal' => [
            'margin_top' => 5,
            'margin_bottom' => 5,
            'margin_left' => 5,
            'margin_right' => 5
        ]
    ];

    public function getDefaultOptions(): array
    {
        return [
            'paper' => 'a4',
            'orientation' => 'portrait',
            'attachment'=>false
        ];
    }

    public function getStyleOptions(string $style = 'default'): array
    {
        if (!isset($this->styles[$style])) {
            return [];
        }

        $options = $this->styles[$style];

        if (isset($options['footer_html'])) {
            $options['footer_html'] = view($options['footer_html'])->render();
        }

        return $options;
    }


    public function generateFileName(array $data): string
    {
        return 'dosya-' . date('Y-m-d') . '.pdf';
    }
}
