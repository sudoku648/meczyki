<?php

declare(strict_types=1);

namespace App\Dto;

use App\Dto\Contracts\UserDtoInterface;
use App\Entity\Person;
use App\Entity\User;
use App\Validator\UniqueEntity;
use DateTimeImmutable;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;

#[UniqueEntity(options: [
    'entityClass' => User::class,
    'errorPaths'  => 'username',
    'fields'      => ['username'],
    'idFields'    => 'id',
])]
class UserDto implements UserDtoInterface
{
    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 35)]
    #[Assert\Regex(pattern: '/^[a-zA-Z0-9_]{2,35}$/', match: true)]
    public ?string $username = null;

    #[Assert\Length(min: 6, max: 4096)]
    public ?string $plainPassword = null;

    #[Assert\Type('boolean')]
    public ?bool $isActive = null;

    public Person|PersonDto|null $person = null;

    public ?Collection $userRoles = null;

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
