<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Traits\CreatedAtTrait;
use App\Entity\Traits\UpdatedAtTrait;
use App\ValueObject\TeamId;

class Team
{
    use CreatedAtTrait {
        CreatedAtTrait::__construct as createdAtTraitConstruct;
    }
    use UpdatedAtTrait;

    private TeamId $id;

    private string $fullName;

    private string $shortName;

    private Club $club;

    public function __construct(
        string $fullName,
        string $shortName,
        Club $club,
    ) {
        $this->id        = TeamId::generate();
        $this->fullName  = $fullName;
        $this->shortName = $shortName;
        $this->club      = $club;

        $this->createdAtTraitConstruct();
    }

    public function getId(): TeamId
    {
        return $this->id;
    }

    public function getFullName(): string
    {
        return $this->fullName;
    }

    public function setFullName(string $fullName): self
    {
        $this->fullName = $fullName;

        return $this;
    }

    public function getShortName(): string
    {
        return $this->shortName;
    }

    public function setShortName(string $shortName): self
    {
        $this->shortName = $shortName;

        return $this;
    }

    public function getClub(): ?Club
    {
        return $this->club;
    }

    public function setClub(?Club $club): self
    {
        $this->club = $club;

        return $this;
    }

    public function __sleep()
    {
        return [];
    }
}
