<?php

declare(strict_types=1);

namespace App\Dto;

use App\Entity\GameType;
use App\Entity\Image;
use App\Validator\UniqueEntity;
use DateTimeImmutable;
use Symfony\Component\Validator\Constraints as Assert;

#[UniqueEntity(options: [
    'entityClass'             => GameType::class,
    'errorPaths'              => 'group',
    'fields'                  => ['name', 'group'],
    'idFields'                => 'id',
    'nullComparisonForFields' => ['group'],
    'message'                 => 'Rozgrywki mają już tę grupę.',
])]
class GameTypeDto
{
    private ?string $id = null;

    #[Assert\NotBlank()]
    #[Assert\Length(min: 2, max: 150)]
    public ?string $name = null;

    #[Assert\Length(max: 150)]
    public ?string $group = null;

    public ?string $fullName = null;

    #[Assert\Type('boolean')]
    public ?bool $isOfficial = null;

    public Image|ImageDto|null $image = null;

    public ?DateTimeImmutable $createdAt = null;

    public ?string $createdAtAgo = null;

    public ?DateTimeImmutable $updatedAt = null;

    public ?string $updatedAtAgo = null;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }
}
