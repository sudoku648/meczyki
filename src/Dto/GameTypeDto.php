<?php

declare(strict_types=1);

namespace App\Dto;

use App\Entity\GameType;
use App\Entity\Image;
use App\Validator\Unique;
use Symfony\Component\Validator\Constraints as Assert;

#[Unique(options: [
    'entityClass' => GameType::class,
    'errorPath' => 'group',
    'fields' => ['name', 'group'],
    'idFields' => 'id',
    'message' => 'Rozgrywki mają już tę grupę.',
])]
class GameTypeDto
{
    #[Assert\NotBlank()]
    #[Assert\Length(min: 2, max: 150)]
    public ?string $name = null;

    #[Assert\Length(max: 150)]
    public ?string $group = null;

    public ?string $fullName = null;

    #[Assert\Type('boolean')]
    public ?bool $isOfficial = null;

    public Image|ImageDto|null $image = null;

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
