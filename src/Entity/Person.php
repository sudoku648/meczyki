<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Enums\VoivodeshipEnum;
use App\Entity\Traits\CreatedAtTrait;
use App\Entity\Traits\PersonTrait;
use App\Entity\Traits\UpdatedAtTrait;
use App\ValueObject\PersonId;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Person
{
    use CreatedAtTrait {
        CreatedAtTrait::__construct as createdAtTraitConstruct;
    }
    use UpdatedAtTrait;
    use PersonTrait {
        PersonTrait::__construct as personTraitConstruct;
    }

    private PersonId $id;

    private ?string $mobilePhone;

    private bool $isDelegate = false;

    private bool $isReferee = false;

    private bool $isRefereeObserver = false;

    private ?string $email = null;

    private ?DateTimeImmutable $dateOfBirth = null;

    private ?string $placeOfBirth = null;

    private ?string $addressTown = null;

    private ?string $addressStreet = null;

    private ?string $addressZipCode = null;

    private ?string $addressPostOffice = null;

    private ?VoivodeshipEnum $addressVoivodeship = null;

    private ?string $addressPowiat = null;

    private ?string $addressGmina = null;

    private ?string $taxOfficeName = null;

    private ?string $taxOfficeAddress = null;

    private ?string $pesel = null;

    private ?string $nip = null;

    private ?string $iban = null;

    private bool $allowsToSendPitByEmail = false;

    private Collection $matchGameBills;

    public function __construct(
        string $firstName,
        string $lastName,
        ?string $mobilePhone = null,
        ?bool $isDelegate = null,
        ?bool $isReferee = null,
        ?bool $isRefereeObserver = null,
    ) {
        $this->id                = PersonId::generate();
        $this->mobilePhone       = $mobilePhone;
        $this->isDelegate        = $isDelegate ?? false;
        $this->isReferee         = $isReferee ?? false;
        $this->isRefereeObserver = $isRefereeObserver ?? false;
        $this->matchGameBills    = new ArrayCollection();

        $this->personTraitConstruct($firstName, $lastName);

        $this->createdAtTraitConstruct();
    }

    public function getId(): PersonId
    {
        return $this->id;
    }

    public function getMobilePhone(): ?string
    {
        return $this->mobilePhone;
    }

    public function setMobilePhone(?string $mobilePhone): self
    {
        $this->mobilePhone = $mobilePhone;

        return $this;
    }

    public function isDelegate(): bool
    {
        return $this->isDelegate;
    }

    public function setIsDelegate(bool $isDelegate): self
    {
        $this->isDelegate = $isDelegate;

        return $this;
    }

    public function isReferee(): bool
    {
        return $this->isReferee;
    }

    public function setIsReferee(bool $isReferee): self
    {
        $this->isReferee = $isReferee;

        return $this;
    }

    public function isRefereeObserver(): bool
    {
        return $this->isRefereeObserver;
    }

    public function setIsRefereeObserver(bool $isRefereeObserver): self
    {
        $this->isRefereeObserver = $isRefereeObserver;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getDateOfBirth(): ?DateTimeImmutable
    {
        return $this->dateOfBirth;
    }

    public function setDateOfBirth(?DateTimeImmutable $date): self
    {
        $this->dateOfBirth = $date;

        return $this;
    }

    public function getPlaceOfBirth(): ?string
    {
        return $this->placeOfBirth;
    }

    public function setPlaceOfBirth(?string $town): self
    {
        $this->placeOfBirth = $town;

        return $this;
    }

    public function getAddressTown(): ?string
    {
        return $this->addressTown;
    }

    public function setAddressTown(?string $town): self
    {
        $this->addressTown = $town;

        return $this;
    }

    public function getAddressStreet(): ?string
    {
        return $this->addressStreet;
    }

    public function setAddressStreet(?string $streetWithNumber): self
    {
        $this->addressStreet = $streetWithNumber;

        return $this;
    }

    public function getAddressZipCode(): ?string
    {
        return $this->addressZipCode;
    }

    public function setAddressZipCode(?string $zipCode): self
    {
        $this->addressZipCode = $zipCode;

        return $this;
    }

    public function getAddressPostOffice(): ?string
    {
        return $this->addressPostOffice;
    }

    public function setAddressPostOffice(?string $postOffice): self
    {
        $this->addressPostOffice = $postOffice;

        return $this;
    }

    public function getAddress(): string
    {
        $address = '';

        if ($this->addressTown) {
            $address .= $this->addressTown;
        }
        if ($this->addressStreet) {
            $address .= '' !== $address ? ', ' . $this->addressStreet : $this->addressStreet;
        }
        if ($this->addressZipCode) {
            $address .= '' !== $address ? ', ' . $this->addressZipCode : $this->addressZipCode;
        }
        if ($this->addressPostOffice && $this->addressZipCode) {
            $address .= $this->addressPostOffice;
        } else {
            $address .= '' !== $address ? ', ' . $this->addressPostOffice : $this->addressPostOffice;
        }

        return $address;
    }

    public function getAddressVoivodeship(): ?VoivodeshipEnum
    {
        return $this->addressVoivodeship;
    }

    public function setAddressVoivodeship(?VoivodeshipEnum $voivodeship): self
    {
        $this->addressVoivodeship = $voivodeship;

        return $this;
    }

    public function getAddressPowiat(): ?string
    {
        return $this->addressPowiat;
    }

    public function setAddressPowiat(?string $powiat): self
    {
        $this->addressPowiat = $powiat;

        return $this;
    }

    public function getAddressGmina(): ?string
    {
        return $this->addressGmina;
    }

    public function setAddressGmina(?string $gmina): self
    {
        $this->addressGmina = $gmina;

        return $this;
    }

    public function getTaxOfficeName(): ?string
    {
        return $this->taxOfficeName;
    }

    public function setTaxOfficeName(?string $name): self
    {
        $this->taxOfficeName = $name;

        return $this;
    }

    public function getTaxOfficeAddress(): ?string
    {
        return $this->taxOfficeAddress;
    }

    public function setTaxOfficeAddress(?string $address): self
    {
        $this->taxOfficeAddress = $address;

        return $this;
    }

    public function getPesel(): ?string
    {
        return $this->pesel;
    }

    public function setPesel(?string $pesel): self
    {
        $this->pesel = $pesel;

        return $this;
    }

    public function getNip(): ?string
    {
        return $this->nip;
    }

    public function setNip(?string $nip): self
    {
        $this->nip = $nip;

        return $this;
    }

    public function getIban(): ?string
    {
        return $this->iban;
    }

    public function setIban(?string $iban): self
    {
        $this->iban = $iban;

        return $this;
    }

    public function allowsToSendPitByEmail(): bool
    {
        return $this->allowsToSendPitByEmail;
    }

    public function setAllowsToSendPitByEmail(bool $isAllowed): self
    {
        $this->allowsToSendPitByEmail = $isAllowed;

        return $this;
    }

    /**
     * @return Collection<int, MatchGameBill>
     */
    public function getMatchGameBills(): Collection
    {
        return $this->matchGameBills;
    }

    public function addMatchGameBill(MatchGameBill $matchGameBill): self
    {
        if (!$this->matchGameBills->contains($matchGameBill)) {
            $this->matchGameBills[] = $matchGameBill;
            $matchGameBill->setPerson($this);
        }

        return $this;
    }

    public function removeMatchGameBill(MatchGameBill $matchGameBill): self
    {
        if ($this->matchGameBills->removeElement($matchGameBill)) {
            // set the owning side to null (unless already changed)
            if ($matchGameBill->getPerson() === $this) {
                $matchGameBill->setPerson(null);
            }
        }

        return $this;
    }

    public function isInMatchGame(MatchGame $matchGame): bool
    {
        return
            $matchGame->getReferee() === $this ||
            $matchGame->getFirstAssistantReferee() === $this ||
            $matchGame->getSecondAssistantReferee() === $this ||
            $matchGame->getFourthOfficial() === $this ||
            $matchGame->getDelegate() === $this ||
            $matchGame->getRefereeObserver() === $this
        ;
    }

    public function hasBillForMatchGame(MatchGame $matchGame): bool
    {
        $bills = $this->matchGameBills->filter(function ($element) use ($matchGame) {
            return $element->getMatchGame() === $matchGame;
        });

        return !$bills->isEmpty();
    }
}
