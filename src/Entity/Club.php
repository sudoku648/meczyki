<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Traits\CreatedAtTrait;
use App\Entity\Traits\UpdatedAtTrait;
use App\Repository\ClubRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClubRepository::class)]
class Club
{
    use CreatedAtTrait {
        CreatedAtTrait::__construct as createdAtTraitConstruct;
    }
    use UpdatedAtTrait;

    #[ORM\Column(type: Types::STRING, length: 150, unique: true)]
    private string $name = '';

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column(type: Types::INTEGER)]
    private int $id;

    public function __construct(string $name)
    {
        $this->name  = $name;

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

    public function getId(): int
    {
        return $this->id;
    }

    public function __sleep(): array
    {
        return [];
    }
}
