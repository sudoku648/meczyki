<?php

declare(strict_types=1);

namespace App\Factory;

use App\Dto\PersonDto;
use App\Entity\Person;
use App\ValueObject\FirstName;
use App\ValueObject\LastName;
use App\ValueObject\PhoneNumber;

class PersonFactory
{
    public function createFromDto(PersonDto $dto): Person
    {
        return new Person(
            FirstName::fromString($dto->firstName),
            LastName::fromString($dto->lastName),
            null !== $dto->mobilePhone ? PhoneNumber::fromString($dto->mobilePhone) : null,
            $dto->isDelegate,
            $dto->isReferee,
            $dto->isRefereeObserver,
        );
    }

    public function createDto(Person $person): PersonDto
    {
        $dto                         = new PersonDto();
        $dto->firstName              = $person->getFirstName()->getValue();
        $dto->lastName               = $person->getLastName()->getValue();
        $dto->fullName               = $person->getFullName();
        $dto->mobilePhone            = $person->getMobilePhone()?->getValue();
        $dto->isDelegate             = $person->isDelegate();
        $dto->isReferee              = $person->isReferee();
        $dto->isRefereeObserver      = $person->isRefereeObserver();
        $dto->email                  = $person->getEmail();
        $dto->dateOfBirth            = $person->getDateOfBirth();
        $dto->placeOfBirth           = $person->getPlaceOfBirth();
        $dto->addressTown            = $person->getAddress()->getTown();
        $dto->addressStreet          = $person->getAddress()->getStreet();
        $dto->addressPostCode        = $person->getAddress()->getPostCode();
        $dto->addressPostOffice      = $person->getAddress()->getPostOffice();
        $dto->addressVoivodeship     = $person->getAddress()->getVoivodeship();
        $dto->addressCounty          = $person->getAddress()->getCounty();
        $dto->addressGmina           = $person->getAddress()->getGmina();
        $dto->taxOfficeName          = $person->getTaxOfficeName();
        $dto->taxOfficeAddress       = $person->getTaxOfficeAddress();
        $dto->pesel                  = $person->getPesel()?->getValue();
        $dto->nip                    = $person->getNip()?->getValue();
        $dto->iban                   = $person->getIban()?->getPrefix() . $person->getIban()?->getNumber();
        $dto->allowsToSendPitByEmail = $person->allowsToSendPitByEmail();
        $dto->setId($person->getId());

        return $dto;
    }
}
