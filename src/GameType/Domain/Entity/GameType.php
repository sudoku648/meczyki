<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\GameType\Domain\Entity;

use Sudoku648\Meczyki\GameType\Domain\ValueObject\GameTypeId;
use Sudoku648\Meczyki\GameType\Domain\ValueObject\GameTypeName;
use Sudoku648\Meczyki\Image\Domain\Entity\Image;
use Sudoku648\Meczyki\Shared\Domain\Entity\CreatedAtTrait;
use Sudoku648\Meczyki\Shared\Domain\Entity\UpdatedAtTrait;

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
        ?bool $isOfficial = null,
    ) {
        $this->id         = GameTypeId::generate();
        $this->name       = $name;
        $this->isOfficial = true === $isOfficial;

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
}
