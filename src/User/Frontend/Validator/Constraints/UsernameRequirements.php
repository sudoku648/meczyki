<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\User\Frontend\Validator\Constraints;

use Attribute;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Compound;

#[Attribute]
class UsernameRequirements extends Compound
{
    protected function getConstraints(array $options): array
    {
        return [
            new Assert\NotBlank(),
            new Assert\Length(min: 2, max: 35),
            new Assert\Regex(pattern: '/^[a-zA-Z0-9_]{2,35}$/', match: true),
        ];
    }
}
