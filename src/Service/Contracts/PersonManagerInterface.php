<?php

declare(strict_types=1);

namespace App\Service\Contracts;

use App\Dto\PersonDto;
use App\Entity\Person;

interface PersonManagerInterface
{
    public function create(PersonDto $dto): Person;

    public function edit(Person $person, PersonDto $dto): Person;

    public function editPersonalInfo(Person $person, PersonDto $dto): Person;

    public function delete(Person $person): void;

    public function createDto(Person $person): PersonDto;
}
