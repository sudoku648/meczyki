<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Team\Frontend\Dto;

use Sudoku648\Meczyki\Club\Domain\Entity\Club;
use Sudoku648\Meczyki\Team\Frontend\Validator\Constraints as TeamAssert;

final class CreateTeamDto
{
    public function __construct(
        #[TeamAssert\NameRequirements]
        public ?string $name = null,
        #[TeamAssert\ShortNameRequirements]
        public ?string $shortName = null,
        public ?Club $club = null,
    ) {
    }
}
