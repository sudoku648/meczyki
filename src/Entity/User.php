<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Traits\CreatedAtTrait;
use App\Entity\Traits\UpdatedAtTrait;
use App\ValueObject\UserId;
use App\ValueObject\Username;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

use function array_unique;
use function in_array;
use function is_null;

class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    use CreatedAtTrait {
        CreatedAtTrait::__construct as createdAtTraitConstruct;
    }
    use UpdatedAtTrait;

    private UserId $id;

    private Username $username;

    private string $password;

    private array $roles = [];

    private bool $isActive = true;

    private ?Person $person;

    public function __construct(
        Username $username,
        string $password,
    ) {
        $this->id       = UserId::generate();
        $this->password = $password;
        $this->username = $username;

        $this->createdAtTraitConstruct();
    }

    public function getId(): UserId
    {
        return $this->id;
    }

    public function getUsername(): Username
    {
        return $this->username;
    }

    public function setUsername(Username $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function isPerson(): bool
    {
        return !is_null($this->person);
    }

    public function getPerson(): ?Person
    {
        return $this->person;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function setOrRemoveSuperAdminRole(bool $remove = false): self
    {
        $this->roles = ['ROLE_SUPER_ADMIN'];

        if ($remove) {
            $this->roles = [];
        }

        return $this;
    }

    public function isSuperAdmin(): bool
    {
        return in_array('ROLE_SUPER_ADMIN', $this->getRoles());
    }

    public function deactivate(): void
    {
        $this->isActive = false;
    }

    public function activate(): void
    {
        $this->isActive = true;
    }

    public function bindWithPerson(Person $person): void
    {
        $this->person = $person;
    }

    public function unbindPerson(): void
    {
        $this->person = null;
    }

    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }
}
