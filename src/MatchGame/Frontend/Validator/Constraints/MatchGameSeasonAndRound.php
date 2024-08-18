<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\MatchGame\Frontend\Validator\Constraints;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute(Attribute::TARGET_CLASS)]
final class MatchGameSeasonAndRound extends Constraint
{
    public function __construct(
        public string $seasonChosenForUnofficialGameType = 'You cannot choose season for unofficial game type.',
        public string $roundChosenForUnofficialGameType = 'You cannot choose round for unofficial game type.',
        mixed $options = null,
        ?array $groups = null,
        mixed $payload = null,
    ) {
        parent::__construct($options, $groups, $payload);
    }

    public function getTargets(): string|array
    {
        return parent::CLASS_CONSTRAINT;
    }
}
