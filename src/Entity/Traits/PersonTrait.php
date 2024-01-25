<?php

declare(strict_types=1);

namespace App\Entity\Traits;

use App\ValueObject\FirstName;
use App\ValueObject\LastName;

trait PersonTrait
{
    private FirstName $firstName;

    private LastName $lastName;

    public function __construct(FirstName $firstName, LastName $lastName)
    {
        $this->firstName = $firstName;
        $this->lastName  = $lastName;
    }

    public function getFirstName(): FirstName
    {
        return $this->firstName;
    }

    public function setFirstName(FirstName $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): LastName
    {
        return $this->lastName;
    }

    public function setLastName(LastName $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getFullName(): string
    {
        return $this->lastName . ' ' . $this->firstName;
    }
}
