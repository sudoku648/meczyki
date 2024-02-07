<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Image\Domain\Service;

interface ImageManagerInterface
{
    public static function isImageUrl(string $url): bool;

    public function store(string $source, string $filePath): bool;

    public function download(string $url): ?string;

    public function getFilePath(string $file): string;

    public function getFileName(string $file): string;

    public function remove(string $path): void;
}
