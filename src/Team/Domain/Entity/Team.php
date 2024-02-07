<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Team\Domain\Entity;

use Sudoku648\Meczyki\Club\Domain\Entity\Club;
use Sudoku648\Meczyki\Shared\Domain\Entity\CreatedAtTrait;
use Sudoku648\Meczyki\Shared\Domain\Entity\UpdatedAtTrait;
use Sudoku648\Meczyki\Team\Domain\ValueObject\TeamId;
use Sudoku648\Meczyki\Team\Domain\ValueObject\TeamName;
use Sudoku648\Meczyki\Team\Domain\ValueObject\TeamShortName;

class Team
{
    use CreatedAtTrait {
        CreatedAtTrait::__construct as createdAtTraitConstruct;
    }
    use UpdatedAtTrait;

    private TeamId $id;
    private TeamName $name;
    private TeamShortName $shortName;
    private Club $club;

    public function __construct(
        TeamName $name,
        TeamShortName $shortName,
        Club $club,
    ) {
        $this->id        = TeamId::generate();
        $this->name      = $name;
        $this->shortName = $shortName;
        $this->club      = $club;

        $this->createdAtTraitConstruct();
    }

    public function getId(): TeamId
    {
        return $this->id;
    }

    public function getName(): TeamName
    {
        return $this->name;
    }

    public function setName(TeamName $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getShortName(): TeamShortName
    {
        return $this->shortName;
    }

    public function setShortName(TeamShortName $shortName): self
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
