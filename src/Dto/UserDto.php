<?php

declare(strict_types=1);

namespace App\Dto;

use App\Dto\Contracts\UserDtoInterface;
use App\Entity\Person;
use App\Entity\User;
use App\Validator\Unique;
use Symfony\Component\Validator\Constraints as Assert;

#[Unique(options: [
    'entityClass' => User::class,
    'errorPath' => 'username',
    'fields' => ['username'],
    'idFields' => 'id',
])]
class UserDto implements UserDtoInterface
{
    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 35)]
    #[Assert\Regex(pattern: "/^[a-zA-Z0-9_]{2,35}$/", match: true)]
    public ?string $username = null;

    #[Assert\Length(min: 6, max: 4096)]
    public ?string $plainPassword = null;

    public Person|PersonDto|null $person = null;

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
