<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Person\Domain\Service;

use Sudoku648\Meczyki\Person\Domain\Entity\Person;
use Sudoku648\Meczyki\Person\Frontend\Dto\CreatePersonDto;
use Sudoku648\Meczyki\Person\Frontend\Dto\EditPersonalInfoDto;
use Sudoku648\Meczyki\Person\Frontend\Dto\UpdatePersonDto;

interface PersonManagerInterface
{
    public function create(CreatePersonDto $dto): Person;

    public function edit(Person $person, UpdatePersonDto $dto): Person;

    public function editPersonalInfo(Person $person, EditPersonalInfoDto $dto): Person;

    public function delete(Person $person): void;
}
