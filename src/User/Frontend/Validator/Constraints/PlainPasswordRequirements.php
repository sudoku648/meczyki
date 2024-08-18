<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\User\Frontend\Validator\Constraints;

use Attribute;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Compound;

#[Attribute]
class PlainPasswordRequirements extends Compound
{
    protected function getConstraints(array $options): array
    {
        return [
            new Assert\Length(min: 6, max: 4096),
        ];
    }
}
