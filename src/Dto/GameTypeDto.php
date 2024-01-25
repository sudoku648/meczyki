<?php

declare(strict_types=1);

namespace App\Dto;

use App\Entity\GameType;
use App\Entity\Image;
use App\Validator\UniqueEntity;
use App\ValueObject\GameTypeId;
use Symfony\Component\Validator\Constraints as Assert;

#[UniqueEntity(options: [
    'entityClass' => GameType::class,
    'errorPaths'  => 'name',
    'fields'      => ['name'],
    'idFields'    => 'id',
    'message'     => 'Nazwa jest już wykorzystywana.',
])]
class GameTypeDto
{
    private ?GameTypeId $id = null;

    #[Assert\NotBlank()]
    #[Assert\Length(min: 2, max: 150)]
    public ?string $name = null;

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
