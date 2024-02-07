<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Club\Frontend\Dto;

use Sudoku648\Meczyki\Club\Domain\Entity\Club;
use Sudoku648\Meczyki\Club\Domain\ValueObject\ClubId;
use Sudoku648\Meczyki\Image\Domain\Entity\Image;
use Sudoku648\Meczyki\Shared\Frontend\Validator\Constraints\UniqueEntity;
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
