<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\MatchGame\Frontend\Validator\Constraints;

use Attribute;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Compound;

#[Attribute]
class HomeTeamRequirements extends Compound
{
    protected function getConstraints(array $options): array
    {
        return [
            new Assert\NotBlank(),
        ];
    }
}
