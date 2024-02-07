<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\User\Frontend\Dto;

use Sudoku648\Meczyki\Person\Domain\Entity\Person;
use Sudoku648\Meczyki\Shared\Frontend\Validator\Constraints\UniqueEntity;
use Sudoku648\Meczyki\User\Domain\ValueObject\UserId;
use Symfony\Component\Validator\Constraints as Assert;

#[UniqueEntity(options: [
    'entityClass' => User::class,
    'errorPaths'  => 'username',
    'fields'      => ['username'],
    'idFields'    => 'id',
])]
class UserDto implements UserDtoInterface
{
    private ?UserId $id = null;

    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 35)]
    #[Assert\Regex(pattern: '/^[a-zA-Z0-9_]{2,35}$/', match: true)]
    public ?string $username = null;

    #[Assert\Length(min: 6, max: 4096)]
    public ?string $plainPassword = null;

    #[Assert\Type('boolean')]
    public ?bool $isActive = null;

    public ?Person $person = null;

    public function getId(): ?UserId
    {
        return $this->id;
    }

    public function setId(UserId $id): void
    {
        $this->id = $id;
    }
}
