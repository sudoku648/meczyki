<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Person\Domain\Entity;

use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Sudoku648\Meczyki\MatchGame\Domain\Entity\MatchGame;
use Sudoku648\Meczyki\MatchGameBill\Domain\Entity\MatchGameBill;
use Sudoku648\Meczyki\Person\Domain\ValueObject\FirstName;
use Sudoku648\Meczyki\Person\Domain\ValueObject\LastName;
use Sudoku648\Meczyki\Person\Domain\ValueObject\MatchGameFunction;
use Sudoku648\Meczyki\Person\Domain\ValueObject\PersonId;
use Sudoku648\Meczyki\Person\Domain\ValueObject\Pesel;
use Sudoku648\Meczyki\Shared\Domain\Entity\CreatedAtTrait;
use Sudoku648\Meczyki\Shared\Domain\Entity\UpdatedAtTrait;
use Sudoku648\Meczyki\Shared\Domain\ValueObject\Address;
use Sudoku648\Meczyki\Shared\Domain\ValueObject\Iban;
use Sudoku648\Meczyki\Shared\Domain\ValueObject\Nip;
use Sudoku648\Meczyki\Shared\Domain\ValueObject\PhoneNumber;

use function array_values;

class Person
{
    use CreatedAtTrait {
        CreatedAtTrait::__construct as createdAtTraitConstruct;
    }
    use UpdatedAtTrait;

    private PersonId $id;
    private FirstName $firstName;
    private LastName $lastName;
    private ?PhoneNumber $mobilePhone;
    /**
     * @var MatchGameFunction[]
     */
    private array $functions                = [];
    private ?string $email                  = null;
    private ?DateTimeImmutable $dateOfBirth = null;
    private ?string $placeOfBirth           = null;
    private Address $address;
    private ?string $taxOfficeName       = null;
    private ?string $taxOfficeAddress    = null;
    private ?Pesel $pesel                = null;
    private ?Nip $nip                    = null;
    private ?Iban $iban                  = null;
    private bool $allowsToSendPitByEmail = false;
    private Collection $matchGameBills;

    public function __construct(
        FirstName $firstName,
        LastName $lastName,
        ?PhoneNumber $mobilePhone = null,
        array $functions = [],
    ) {
        $this->id             = PersonId::generate();
        $this->firstName      = $firstName;
        $this->lastName       = $lastName;
        $this->mobilePhone    = $mobilePhone;
        $this->functions      = $functions;
        $this->address        = new Address();
        $this->matchGameBills = new ArrayCollection();

        $this->createdAtTraitConstruct();
    }

    public function getId(): PersonId
    {
        return $this->id;
    }

    public function getFirstName(): FirstName
    {
        return $this->firstName;
    }

    public function setFirstName(FirstName $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): LastName
    {
        return $this->lastName;
    }

    public function setLastName(LastName $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getFullName(): string
    {
        return "$this->lastName $this->firstName";
    }

    public function getMobilePhone(): ?PhoneNumber
    {
        return $this->mobilePhone;
    }

    public function setMobilePhone(?PhoneNumber $mobilePhone): self
    {
        $this->mobilePhone = $mobilePhone;

        return $this;
    }

    public function getFunctions(): array
    {
        return $this->functions;
    }

    public function setFunctions(array $functions): self
    {
        $this->functions = array_values($functions);

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

    public function getAddress(): Address
    {
        return $this->address;
    }

    public function setAddress(Address $address): self
    {
        $this->address = $address;

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

    public function getPesel(): ?Pesel
    {
        return $this->pesel;
    }

    public function setPesel(?Pesel $pesel): self
    {
        $this->pesel = $pesel;

        return $this;
    }

    public function getNip(): ?Nip
    {
        return $this->nip;
    }

    public function setNip(?Nip $nip): self
    {
        $this->nip = $nip;

        return $this;
    }

    public function getIban(): ?Iban
    {
        return $this->iban;
    }

    public function setIban(?Iban $iban): self
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
