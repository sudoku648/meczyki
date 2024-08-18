<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Person\Frontend\Validator\Constraints;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute(Attribute::TARGET_CLASS)]
final class PersonDateOfBirth extends Constraint
{
    public function __construct(
        public string $dateOfBirthInFuture = 'Date of birth cannot be from the future.',
        public string $peselNotExists = 'PESEL doesn\'t exist for this date of birth.',
        public string $peselNotMatchWithDateOfBirth = 'PESEL doesn\'t match with date of birth.',
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
