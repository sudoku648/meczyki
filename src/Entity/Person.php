<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Traits\CreatedAtTrait;
use App\Entity\Traits\PersonTrait;
use App\Entity\Traits\UpdatedAtTrait;
use App\Repository\PersonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PersonRepository::class)]
class Person
{
    use CreatedAtTrait {
        CreatedAtTrait::__construct as createdAtTraitConstruct;
    }
    use UpdatedAtTrait;
    use PersonTrait {
        PersonTrait::__construct as personTraitConstruct;
    }

    #[ORM\Column(type: Types::STRING, length: 12, unique: true, nullable: true, options: ['default' => null,])]
    private ?string $mobilePhone;

    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $isDelegate = false;

    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $isReferee = false;

    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $isRefereeObserver = false;

    #[ORM\OneToMany(targetEntity: MatchGameBill::class, mappedBy: 'person')]
    private Collection $matchGameBills;

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column(type: Types::INTEGER)]
    private int $id;

    public function __construct(
        string $firstName,
        string $lastName,
        ?string $mobilePhone = null,
        ?bool $isDelegate = null,
        ?bool $isReferee = null,
        ?bool $isRefereeObserver = null
    )
    {
        $this->mobilePhone       = $mobilePhone;
        $this->isDelegate        = $isDelegate ?? false;
        $this->isReferee         = $isReferee ?? false;
        $this->isRefereeObserver = $isRefereeObserver ?? false;
        $this->matchGameBills    = new ArrayCollection();

        $this->personTraitConstruct($firstName, $lastName);

        $this->createdAtTraitConstruct();
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
            $matchGame->getReferee()                === $this ||
            $matchGame->getFirstAssistantReferee()  === $this ||
            $matchGame->getSecondAssistantReferee() === $this ||
            $matchGame->getFourthOfficial()         === $this ||
            $matchGame->getDelegate()               === $this ||
            $matchGame->getRefereeObserver()        === $this
        ;
    }

    public function hasBillForMatchGame(MatchGame $matchGame): bool
    {
        $bills = $this->matchGameBills->filter(function($element) use ($matchGame) {
            return $element->getMatchGame() === $matchGame;
        });

        return !$bills->isEmpty();
    }

    public function getId(): int
    {
        return $this->id;
    }
}
