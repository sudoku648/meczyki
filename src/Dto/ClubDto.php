<?php

declare(strict_types=1);

namespace App\Dto;

use App\Entity\Club;
use App\Entity\Image;
use App\Validator\Unique;
use Symfony\Component\Validator\Constraints as Assert;

#[Unique(options: [
    'entityClass' => Club::class,
    'errorPath' => 'name',
    'fields' => ['name'],
    'idFields' => 'id',
    'message' => 'Nazwa jest już wykorzystywana.',
])]
class ClubDto
{
    #[Assert\NotBlank()]
    #[Assert\Length(min: 2, max: 150)]
    public ?string $name = null;

    public Image|ImageDto|null $emblem = null;

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
