<?php

declare(strict_types=1);

namespace App\Factory;

use App\Dto\PersonDto;
use App\Entity\Person;
use App\Factory\Contracts\ContentFactoryInterface;

class PersonFactory implements ContentFactoryInterface
{
    public function createFromDto(PersonDto $dto): Person
    {
        return new Person(
            $dto->firstName,
            $dto->lastName,
            $dto->mobilePhone,
            $dto->isDelegate,
            $dto->isReferee,
            $dto->isRefereeObserver
        );
    }

    public function createDto(Person $person): PersonDto
    {
        $dto = new PersonDto();

        $dto->firstName         = $person->getFirstName();
        $dto->lastName          = $person->getLastName();
        $dto->fullName          = $person->getFullName();
        $dto->fullNameInversed  = $person->getFullNameInversed();
        $dto->mobilePhone       = $person->getMobilePhone();
        $dto->isDelegate        = $person->isDelegate();
        $dto->isReferee         = $person->isReferee();
        $dto->isRefereeObserver = $person->isRefereeObserver();
        $dto->createdAt         = $person->getCreatedAt();
        $dto->updatedAt         = $person->getUpdatedAt();
        $dto->setId($person->getId());

        return $dto;
    }
}
