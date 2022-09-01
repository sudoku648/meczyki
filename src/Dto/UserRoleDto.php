<?php

declare(strict_types=1);

namespace App\Dto;

use App\Entity\UserRole;
use App\Validator\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[UniqueEntity(options: [
    'entityClass' => UserRole::class,
    'errorPaths'  => 'name',
    'fields'      => ['name'],
    'idFields'    => 'id',
])]
class UserRoleDto
{
    #[Assert\NotBlank()]
    #[Assert\Length(min: 2, max: 180)]
    public ?string $name = null;

    public ?array $permissions = null;

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
