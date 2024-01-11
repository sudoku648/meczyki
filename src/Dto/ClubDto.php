<?php

declare(strict_types=1);

namespace App\Dto;

use App\Entity\Club;
use App\Entity\Image;
use App\Validator\UniqueEntity;
use App\ValueObject\ClubId;
use Symfony\Component\Validator\Constraints as Assert;

#[UniqueEntity(options: [
    'entityClass' => Club::class,
    'errorPaths'  => 'name',
    'fields'      => ['name'],
    'idFields'    => 'id',
    'message'     => 'Nazwa jest już wykorzystywana.',
])]
class ClubDto
{
    private ?ClubId $id = null;

    #[Assert\NotBlank()]
    #[Assert\Length(min: 2, max: 150)]
    public ?string $name = null;

    public ?Image $emblem = null;

    public function getId(): ?ClubId
    {
        return $this->id;
    }

    public function setId(ClubId $id): void
    {
        $this->id = $id;
    }
}
