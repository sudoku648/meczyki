<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Image\Domain\Message;

final readonly class DeleteImageMessage
{
    public function __construct(
        private string $path,
    ) {
    }

    public function getPath(): string
    {
        return $this->path;
    }
}
