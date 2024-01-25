<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\PersonDto;
use App\Entity\Person;
use App\Event\Person\PersonCreatedEvent;
use App\Event\Person\PersonDeletedEvent;
use App\Event\Person\PersonPersonalInfoUpdatedEvent;
use App\Event\Person\PersonUpdatedEvent;
use App\Factory\PersonFactory;
use App\Service\Contracts\PersonManagerInterface;
use App\ValueObject\Address;
use App\ValueObject\FirstName;
use App\ValueObject\Iban;
use App\ValueObject\LastName;
use App\ValueObject\Nip;
use App\ValueObject\Pesel;
use App\ValueObject\PhoneNumber;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

readonly class PersonManager implements PersonManagerInterface
{
    public function __construct(
        private EventDispatcherInterface $dispatcher,
        private EntityManagerInterface $entityManager,
        private PersonFactory $factory,
    ) {
    }

    public function create(PersonDto $dto): Person
    {
        $person = $this->factory->createFromDto($dto);

        $this->entityManager->persist($person);
        $this->entityManager->flush();

        $this->dispatcher->dispatch(new PersonCreatedEvent($person));

        return $person;
    }

    public function edit(Person $person, PersonDto $dto): Person
    {
        $person->setFirstName(FirstName::fromString($dto->firstName));
        $person->setLastName(LastName::fromString($dto->lastName));
        $person->setMobilePhone(null !== $dto->mobilePhone ? PhoneNumber::fromString($dto->mobilePhone) : null);
        $person->setIsDelegate($dto->isDelegate);
        $person->setIsReferee($dto->isReferee);
        $person->setIsRefereeObserver($dto->isRefereeObserver);
        $person->setUpdatedAt();

        $this->entityManager->flush();

        $this->dispatcher->dispatch(new PersonUpdatedEvent($person));

        return $person;
    }

    public function editPersonalInfo(Person $person, PersonDto $dto): Person
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

        $this->entityManager->flush();

        $this->dispatcher->dispatch(new PersonPersonalInfoUpdatedEvent($person));

        return $person;
    }

    public function delete(Person $person): void
    {
        $this->dispatcher->dispatch(new PersonDeletedEvent($person));

        $this->entityManager->remove($person);
        $this->entityManager->flush();
    }

    public function createDto(Person $person): PersonDto
    {
        return $this->factory->createDto($person);
    }
}
