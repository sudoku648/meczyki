<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Club\Frontend\Validator\Constraints;

use Attribute;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Compound;

#[Attribute]
class NameRequirements extends Compound
{
    protected function getConstraints(array $options): array
    {
        return [
            new Assert\NotBlank(),
            new Assert\Type('string'),
            new Assert\Length(min: 2, max: 150),
        ];
    }
}
