<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\User\Frontend\Dto;

use Sudoku648\Meczyki\Person\Domain\Entity\Person;

class UserBindWithPersonDto
{
    public function __construct(
        public ?Person $person = null,
    ) {
    }
}
