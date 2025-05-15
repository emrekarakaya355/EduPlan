<?php

namespace App\Contracts;

interface PdfStrategyInterface
{
    public function getView(): string;

    public function getDefaultOptions(): array;

    public function generateFileName(array $data): string;

}
