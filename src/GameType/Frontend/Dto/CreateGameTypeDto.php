<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\GameType\Frontend\Dto;

use Sudoku648\Meczyki\GameType\Frontend\Validator\Constraints as GameTypeAssert;
use Sudoku648\Meczyki\Image\Domain\Entity\Image;
use Symfony\Component\Validator\Constraints as Assert;

#[GameTypeAssert\GameTypeUnique]
final class CreateGameTypeDto
{
    public function __construct(
        #[GameTypeAssert\NameRequirements]
        public ?string $name = null,
        #[Assert\Type('boolean')]
        public ?bool $isOfficial = null,
        public ?Image $image = null,
    ) {
    }
}
