<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\GameType\Frontend\Dto;

use Sudoku648\Meczyki\GameType\Domain\ValueObject\GameTypeId;
use Sudoku648\Meczyki\GameType\Frontend\Validator\Constraints\GameTypeUnique;
use Sudoku648\Meczyki\Image\Domain\Entity\Image;
use Symfony\Component\Validator\Constraints as Assert;

#[GameTypeUnique]
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
