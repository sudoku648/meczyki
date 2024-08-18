<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\MatchGameBill\Frontend\Validator\Constraints;

use Attribute;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Compound;

#[Attribute]
class PercentOfBaseEquivalentRequirements extends Compound
{
    protected function getConstraints(array $options): array
    {
        return [
            new Assert\NotBlank(),
            new Assert\Range(min: 0, max: 100),
        ];
    }
}
