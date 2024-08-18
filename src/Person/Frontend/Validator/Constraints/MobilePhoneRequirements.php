<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Person\Frontend\Validator\Constraints;

use Attribute;
use Sudoku648\Meczyki\Shared\Frontend\Validator\Constraints as SharedAssert;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Compound;

#[Attribute]
class MobilePhoneRequirements extends Compound
{
    protected function getConstraints(array $options): array
    {
        return [
            new Assert\Length(min: 12, max: 12),
            new SharedAssert\PolishMobilePhone(),
        ];
    }
}
