<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Person\Domain\Service;

use Sudoku648\Meczyki\Person\Domain\Entity\Person;
use Sudoku648\Meczyki\Person\Frontend\Dto\PersonDto;

interface PersonManagerInterface
{
    public function create(PersonDto $dto): Person;

    public function edit(Person $person, PersonDto $dto): Person;

    public function editPersonalInfo(Person $person, PersonDto $dto): Person;

    public function delete(Person $person): void;

    public function createDto(Person $person): PersonDto;
}
