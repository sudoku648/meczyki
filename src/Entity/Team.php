<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Traits\CreatedAtTrait;
use App\Entity\Traits\UpdatedAtTrait;
use App\Repository\TeamRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TeamRepository::class)]
class Team
{
    use CreatedAtTrait {
        CreatedAtTrait::__construct as createdAtTraitConstruct;
    }
    use UpdatedAtTrait;

    #[ORM\Column(type: Types::STRING, length: 200)]
    private string $fullName = '';

    #[ORM\Column(type: Types::STRING, length: 150)]
    private string $shortName = '';

    #[ORM\ManyToOne(targetEntity: Club::class, inversedBy: 'teams')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'cascade')]
    private Club $club;

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column(type: Types::INTEGER)]
    private int $id;

    public function __construct(
        string $fullName,
        string $shortName,
        Club $club
    )
    {
        $this->fullName  = $fullName;
        $this->shortName = $shortName;
        $this->club      = $club;

        $this->createdAtTraitConstruct();
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

    public function getId(): int
    {
        return $this->id;
    }

    public function __sleep()
    {
        return [];
    }
}
