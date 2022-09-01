<?php

declare(strict_types=1);

namespace App\Message;

class DeleteImageMessage
{
    public function __construct(
        private readonly string $path
    ) {
    }

    public function getPath(): string
    {
        return $this->path;
    }
}
