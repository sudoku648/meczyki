<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Enums\PermissionEnum;
use App\Entity\Traits\CreatedAtTrait;
use App\Entity\Traits\UpdatedAtTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use function in_array;

class UserRole
{
    use CreatedAtTrait {
        CreatedAtTrait::__construct as createdAtTraitConstruct;
    }
    use UpdatedAtTrait;

    private string $id;

    private string $name;

    private array $permissions;

    private Collection $users;

    public function __construct(
        string $name,
        array $permissions = [],
    ) {
        $this->name        = $name;
        $this->permissions = $permissions;
        $this->users       = new ArrayCollection();

        $this->createdAtTraitConstruct();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPermissions(): array
    {
        return $this->permissions;
    }

    public function setPermissions(array $permissions): self
    {
        $this->permissions = $permissions;

        return $this;
    }

    public function hasPermission(PermissionEnum $permission): bool
    {
        return in_array($permission, $this->permissions);
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->addUserRole($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            $user->removeUserRole($this);
        }

        return $this;
    }

    public function __sleep()
    {
        return [];
    }
}
