<?php

declare(strict_types=1);

namespace App\Dto;

use App\Entity\Person;
use App\Validator\PolishMobilePhone;
use App\Validator\Unique;
use Symfony\Component\Validator\Constraints as Assert;

#[Unique(options: [
    'entityClass' => Person::class,
    'errorPath' => 'mobilePhone',
    'fields' => ['mobilePhone'],
    'idFields' => 'id',
    'message' => 'Wartość jest już wykorzystywana.',
])]
class PersonDto
{
    #[Assert\NotBlank()]
    #[Assert\Length(min: 2, max: 100)]
    public ?string $firstName = null;

    #[Assert\NotBlank()]
    #[Assert\Length(min: 2, max: 100)]
    public ?string $lastName = null;

    public ?string $fullName = null;

    public ?string $fullNameInversed = null;

    #[Assert\Length(min: 12, max: 12)]
    #[PolishMobilePhone()]
    public ?string $mobilePhone = null;

    #[Assert\Type('boolean')]
    public ?bool $isDelegate = null;

    #[Assert\Type('boolean')]
    public ?bool $isReferee = null;

    #[Assert\Type('boolean')]
    public ?bool $isRefereeObserver = null;

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
