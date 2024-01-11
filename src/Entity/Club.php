<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Traits\CreatedAtTrait;
use App\Entity\Traits\UpdatedAtTrait;
use App\ValueObject\ClubId;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Club
{
    use CreatedAtTrait {
        CreatedAtTrait::__construct as createdAtTraitConstruct;
    }
    use UpdatedAtTrait;

    private ClubId $id;

    private string $name;

    private ?Image $emblem = null;

    private Collection $teams;

    public function __construct(string $name)
    {
        $this->id    = ClubId::generate();
        $this->name  = $name;
        $this->teams = new ArrayCollection();

        $this->createdAtTraitConstruct();
    }

    public function getId(): ClubId
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getEmblem(): ?Image
    {
        return $this->emblem;
    }

    public function setEmblem(?Image $emblem): self
    {
        $this->emblem = $emblem;

        return $this;
    }

    /**
     * @return Collection|Team[]
     */
    public function getTeams(): Collection
    {
        return $this->teams;
    }

    public function addTeam(Team $team): self
    {
        if (!$this->teams->contains($team)) {
            $this->teams[] = $team;
            $team->setClub($this);
        }

        return $this;
    }

    public function removeTeam(Team $team): self
    {
        if ($this->teams->removeElement($team)) {
            // set the owning side to null (unless already changed)
            if ($team->getClub() === $this) {
                $team->setClub(null);
            }
        }

        return $this;
    }

    public function __sleep(): array
    {
        return [];
    }
}
