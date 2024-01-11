<?php

declare(strict_types=1);

namespace App\Dto;

use App\Entity\GameType;
use App\Entity\Image;
use App\Validator\UniqueEntity;
use App\ValueObject\GameTypeId;
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
    private ?GameTypeId $id = null;

    #[Assert\NotBlank()]
    #[Assert\Length(min: 2, max: 150)]
    public ?string $name = null;

    #[Assert\Length(max: 150)]
    public ?string $group = null;

    public ?string $fullName = null;

    #[Assert\Type('boolean')]
    public ?bool $isOfficial = null;

    public ?Image $image = null;

    public function getId(): ?GameTypeId
    {
        return $this->id;
    }

    public function setId(GameTypeId $id): void
    {
        $this->id = $id;
    }
}
