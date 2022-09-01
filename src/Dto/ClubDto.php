<?php

declare(strict_types=1);

namespace App\Dto;

use App\Entity\Club;
use App\Entity\Image;
use App\Validator\UniqueEntity;
use DateTimeImmutable;
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
    #[Assert\NotBlank()]
    #[Assert\Length(min: 2, max: 150)]
    public ?string $name = null;

    public Image|ImageDto|null $emblem = null;

    public ?DateTimeImmutable $createdAt = null;

    public ?string $createdAtAgo = null;

    public ?DateTimeImmutable $updatedAt = null;

    public ?string $updatedAtAgo = null;

    private ?int $id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }
}
