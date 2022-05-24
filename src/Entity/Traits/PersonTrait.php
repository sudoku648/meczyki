<?php

declare(strict_types=1);

namespace App\Entity\Traits;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

trait PersonTrait
{
    #[ORM\Column(type: Types::STRING, length: 100)]
    private string $firstName = '';

    #[ORM\Column(type: Types::STRING, length: 100)]
    private string $lastName = '';

    public function __construct(string $firstName, string $lastName)
    {
        $this->firstName = $firstName;
        $this->lastName  = $lastName;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getFullName(): string
    {
        return $this->lastName.' '.$this->firstName;
    }

    public function getFullNameInversed(): string
    {
        return $this->firstName.' '.$this->lastName;
    }
}
