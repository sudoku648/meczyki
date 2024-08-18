<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Team\Frontend\Dto;

use Sudoku648\Meczyki\Club\Domain\Entity\Club;
use Sudoku648\Meczyki\Team\Domain\ValueObject\TeamId;
use Sudoku648\Meczyki\Team\Frontend\Validator\Constraints as TeamAssert;

final class TeamDto
{
    public function __construct(
        public ?TeamId $teamId = null,
        #[TeamAssert\NameRequirements]
        public ?string $name = null,
        #[TeamAssert\ShortNameRequirements]
        public ?string $shortName = null,
        public ?Club $club = null,
    ) {
    }
}
