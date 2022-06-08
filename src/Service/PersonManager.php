<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\PersonDto;
use App\Entity\Person;
use App\Event\Person\PersonCreatedEvent;
use App\Event\Person\PersonDeletedEvent;
use App\Event\Person\PersonUpdatedEvent;
use App\Factory\PersonFactory;
use App\Service\Contracts\ContentManagerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class PersonManager implements ContentManagerInterface
{
    private EventDispatcherInterface $dispatcher;
    private EntityManagerInterface $entityManager;
    private PersonFactory $factory;

    public function __construct(
        EventDispatcherInterface $dispatcher,
        EntityManagerInterface $entityManager,
        PersonFactory $factory
    )
    {
        $this->dispatcher    = $dispatcher;
        $this->entityManager = $entityManager;
        $this->factory       = $factory;
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
        $person->setFirstName($dto->firstName);
        $person->setLastName($dto->lastName);
        $person->setMobilePhone($dto->mobilePhone);
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
        $person->setAddressTown($dto->addressTown);
        $person->setAddressStreet($dto->addressStreet);
        $person->setAddressZipCode($dto->addressZipCode);
        $person->setAddressPostOffice($dto->addressPostOffice);
        $person->setAddressVoivodeship($dto->addressVoivodeship);
        $person->setAddressPowiat($dto->addressPowiat);
        $person->setAddressGmina($dto->addressGmina);
        $person->setTaxOfficeName($dto->taxOfficeName);
        $person->setTaxOfficeAddress($dto->taxOfficeAddress);
        $person->setPesel($dto->pesel);
        $person->setNip($dto->nip);
        $person->setBankAccountNumber($dto->bankAccountNumber);

        $this->entityManager->flush();

        // $this->dispatcher->dispatch(new PersonUpdatedEvent($person));

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
