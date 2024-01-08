<?php

declare(strict_types=1);

namespace App\Message;

readonly class DeleteImageMessage
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
