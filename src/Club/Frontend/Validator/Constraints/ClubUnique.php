<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Club\Frontend\Validator\Constraints;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute(Attribute::TARGET_CLASS)]
final class ClubUnique extends Constraint
{
    protected const string NAME_EXISTS_MESSAGE = 'Name already exists.';

    public function __construct(
        public string $nameExists = self::NAME_EXISTS_MESSAGE,
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
