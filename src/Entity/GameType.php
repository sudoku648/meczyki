<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Traits\CreatedAtTrait;
use App\Entity\Traits\UpdatedAtTrait;
use App\ValueObject\GameTypeId;
use App\ValueObject\GameTypeName;

class GameType
{
    use CreatedAtTrait {
        CreatedAtTrait::__construct as createdAtTraitConstruct;
    }
    use UpdatedAtTrait;

    private GameTypeId $id;

    private GameTypeName $name;

    private bool $isOfficial;

    private ?Image $image = null;

    public function __construct(
        GameTypeName $name,
        bool $isOfficial,
    ) {
        $this->id         = GameTypeId::generate();
        $this->name       = $name;
        $this->isOfficial = $isOfficial;

        $this->createdAtTraitConstruct();
    }

    public function getId(): GameTypeId
    {
        return $this->id;
    }

    public function getName(): GameTypeName
    {
        return $this->name;
    }

    public function setName(GameTypeName $name): self
    {
        $this->name = $name;

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

    public function __sleep(): array
    {
        return [];
    }
}
