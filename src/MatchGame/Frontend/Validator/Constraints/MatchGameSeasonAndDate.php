<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\MatchGame\Frontend\Validator\Constraints;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute(Attribute::TARGET_CLASS)]
final class MatchGameSeasonAndDate extends Constraint
{
    public function __construct(
        public string $seasonNotMatchDate = 'Chosen season doesn\'t match with date.',
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
