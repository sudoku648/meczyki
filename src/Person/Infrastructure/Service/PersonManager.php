<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Person\Infrastructure\Service;

use Sudoku648\Meczyki\Person\Domain\Entity\Person;
use Sudoku648\Meczyki\Person\Domain\Persistence\PersonRepositoryInterface;
use Sudoku648\Meczyki\Person\Domain\Service\PersonManagerInterface;
use Sudoku648\Meczyki\Person\Domain\ValueObject\FirstName;
use Sudoku648\Meczyki\Person\Domain\ValueObject\LastName;
use Sudoku648\Meczyki\Person\Domain\ValueObject\Pesel;
use Sudoku648\Meczyki\Person\Frontend\Dto\EditPersonalInfoDto;
use Sudoku648\Meczyki\Person\Frontend\Dto\PersonDto;
use Sudoku648\Meczyki\Person\Infrastructure\Mapper\MatchGameFunctionMapper;
use Sudoku648\Meczyki\Shared\Domain\ValueObject\Address;
use Sudoku648\Meczyki\Shared\Domain\ValueObject\Iban;
use Sudoku648\Meczyki\Shared\Domain\ValueObject\Nip;
use Sudoku648\Meczyki\Shared\Domain\ValueObject\PhoneNumber;

readonly class PersonManager implements PersonManagerInterface
{
    public function __construct(
        private PersonRepositoryInterface $repository,
        private MatchGameFunctionMapper $functionMapper,
    ) {
    }

    public function create(PersonDto $dto): Person
    {
        $person = new Person(
            FirstName::fromString($dto->firstName),
            LastName::fromString($dto->lastName),
            null !== $dto->mobilePhone ? PhoneNumber::fromString($dto->mobilePhone) : null,
            $this->functionMapper->mapEnumsToValues($dto->functions),
        );

        $this->repository->persist($person);

        return $person;
    }

    public function edit(Person $person, PersonDto $dto): Person
    {
        $person->setFirstName(FirstName::fromString($dto->firstName));
        $person->setLastName(LastName::fromString($dto->lastName));
        $person->setMobilePhone(null !== $dto->mobilePhone ? PhoneNumber::fromString($dto->mobilePhone) : null);
        $person->setFunctions($this->functionMapper->mapEnumsToValues($dto->functions));
        $person->setUpdatedAt();

        $this->repository->persist($person);

        return $person;
    }

    public function editPersonalInfo(Person $person, EditPersonalInfoDto $dto): Person
    {
        $person->setEmail($dto->email);
        $person->setDateOfBirth($dto->dateOfBirth);
        $person->setPlaceOfBirth($dto->placeOfBirth);
        $address = new Address(
            $dto->addressTown,
            $dto->addressStreet,
            $dto->addressPostCode,
            $dto->addressPostOffice,
            $dto->addressVoivodeship,
            $dto->addressCounty,
            $dto->addressGmina,
        );
        $person->setAddress($address);
        $person->setTaxOfficeName($dto->taxOfficeName);
        $person->setTaxOfficeAddress($dto->taxOfficeAddress);
        $person->setPesel(null !== $dto->pesel ? Pesel::fromString($dto->pesel) : null);
        $person->setNip(null !== $dto->nip ? Nip::fromString($dto->nip) : null);
        $person->setIban(null !== $dto->iban ? Iban::fromString($dto->iban) : null);
        $person->setAllowsToSendPitByEmail($dto->allowsToSendPitByEmail);

        $this->repository->persist($person);

        return $person;
    }

    public function delete(Person $person): void
    {
        $this->repository->remove($person);
    }
}
