<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Traits\CreatedAtTrait;
use App\Entity\Traits\UpdatedAtTrait;
use App\ValueObject\GameTypeId;

class GameType
{
    use CreatedAtTrait {
        CreatedAtTrait::__construct as createdAtTraitConstruct;
    }
    use UpdatedAtTrait;

    private GameTypeId $id;

    private string $name;

    private ?string $group;

    private bool $isOfficial;

    private ?Image $image = null;

    public function __construct(
        string $name,
        ?string $group,
        bool $isOfficial,
    ) {
        $this->id         = GameTypeId::generate();
        $this->name       = $name;
        $this->group      = $group;
        $this->isOfficial = $isOfficial;

        $this->createdAtTraitConstruct();
    }

    public function getId(): GameTypeId
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

    public function getGroup(): ?string
    {
        return $this->group;
    }

    public function setGroup(?string $group): self
    {
        $this->group = $group;

        return $this;
    }

    public function isOfficial(): bool
    {
        return $this->isOfficial;
    }

    public function setIsOfficial(bool $isOfficial): self
    {
        $this->isOfficial = $isOfficial;

        return $this;
    }

    public function getImage(): ?Image
    {
        return $this->image;
    }

    public function setImage(?Image $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getFullName(): string
    {
        return $this->name . ($this->group ? ' Grupa ' . $this->group : '');
    }

    public function __sleep(): array
    {
        return [];
    }
}
