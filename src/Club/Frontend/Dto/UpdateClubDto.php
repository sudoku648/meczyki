<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Club\Frontend\Dto;

use Sudoku648\Meczyki\Club\Domain\ValueObject\ClubId;
use Sudoku648\Meczyki\Club\Frontend\Validator\Constraints\ClubUnique;
use Sudoku648\Meczyki\Club\Frontend\Validator\Constraints\NameRequirements;
use Sudoku648\Meczyki\Image\Domain\Entity\Image;

#[ClubUnique]
final class UpdateClubDto
{
    public function __construct(
        public ClubId $clubId,
        #[NameRequirements]
        public ?string $name = null,
        public ?Image $emblem = null,
    ) {
    }
}
