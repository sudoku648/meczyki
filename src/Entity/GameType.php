<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Traits\CreatedAtTrait;
use App\Entity\Traits\UpdatedAtTrait;
use App\Repository\GameTypeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GameTypeRepository::class)]
class GameType
{
    use CreatedAtTrait {
        CreatedAtTrait::__construct as createdAtTraitConstruct;
    }
    use UpdatedAtTrait;

    #[ORM\Column(type: Types::STRING, length: 150)]
    private string $name = '';

    #[ORM\Column(type: Types::STRING, length: 150, nullable: true, name: '`group`')]
    private ?string $group;

    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $isOfficial;

    #[ORM\ManyToOne(targetEntity: Image::class, cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: true)]
    private ?Image $image = null;

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column(type: Types::INTEGER)]
    private int $id;

    public function __construct(
        string $name,
        ?string $group,
        bool $isOfficial
    ) {
        $this->name       = $name;
        $this->group      = $group;
        $this->isOfficial = $isOfficial;

        $this->createdAtTraitConstruct();
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

    public function getId(): int
    {
        return $this->id;
    }

    public function __sleep(): array
    {
        return [];
    }
}
