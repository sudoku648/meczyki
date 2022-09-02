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
    private ?string $id = null;

    #[Assert\NotBlank()]
    #[Assert\Length(min: 2, max: 180)]
    public ?string $name = null;

    public ?array $permissions = null;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }
}
