<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\User\Frontend\Validator\Constraints;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute(Attribute::TARGET_CLASS)]
final class UserUnique extends Constraint
{
    public function __construct(
        public string $usernameExists = 'This value is already used.',// @TODO
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
