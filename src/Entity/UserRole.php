<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Enums\PermissionEnum;
use App\Entity\Traits\CreatedAtTrait;
use App\Entity\Traits\UpdatedAtTrait;
use App\Repository\UserRoleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

use function in_array;

#[ORM\Entity(repositoryClass: UserRoleRepository::class)]
class UserRole
{
    use CreatedAtTrait {
        CreatedAtTrait::__construct as createdAtTraitConstruct;
    }
    use UpdatedAtTrait;

    #[ORM\Column(type: Types::STRING, length: 180, unique: true)]
    private string $name;

    #[ORM\Column(type: Types::JSON, nullable: true)]
    private array $permissions = [];

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'userRoles')]
    private Collection $users;

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[ORM\Column(type: Types::GUID)]
    private string $id;

    public function __construct(
        string $name,
        array $permissions = []
    ) {
        $this->name        = $name;
        $this->permissions = $permissions;
        $this->users       = new ArrayCollection();

        $this->createdAtTraitConstruct();
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

    public function getId(): string
    {
        return $this->id;
    }

    public function __sleep()
    {
        return [];
    }
}
