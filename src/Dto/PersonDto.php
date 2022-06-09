<?php

declare(strict_types=1);

namespace App\Dto;

use App\Entity\Person;
use App\Validator\Nip;
use App\Validator\Pesel;
use App\Validator\PolishMobilePhone;
use App\Validator\Unique;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

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

    #[Assert\Email()]
    public ?string $email = null;

    public ?\DateTimeImmutable $dateOfBirth = null;

    #[Assert\Length(min: 2, max: 100)]
    public ?string $placeOfBirth = null;

    #[Assert\Length(min: 2, max: 100)]
    public ?string $addressTown = null;

    #[Assert\Length(min: 2, max: 100)]
    public ?string $addressStreet = null;

    // @todo validate
    #[Assert\Length(min: 6, max: 6)]
    public ?string $addressZipCode = null;

    #[Assert\Length(min: 2, max: 100)]
    public ?string $addressPostOffice = null;

    #[Assert\Choice(choices: [
        'dolnośląskie', 'kujawsko-pomorskie', 'lubelskie', 'lubuskie',
        'łódzkie', 'małopolskie', 'mazowieckie', 'opolskie',
        'podkarpackie', 'podlaskie', 'pomorskie', 'śląskie',
        'świętokrzyskie', 'warmińsko-mazurskie', 'wielkopolskie', 'zachodniopomorskie',
    ])]
    public ?string $addressVoivodeship = null;

    #[Assert\Length(min: 2, max: 100)]
    public ?string $addressPowiat = null;

    #[Assert\Length(min: 2, max: 100)]
    public ?string $addressGmina = null;

    #[Assert\Length(min: 2, max: 150)]
    public ?string $taxOfficeName = null;

    #[Assert\Length(min: 2, max: 150)]
    public ?string $taxOfficeAddress = null;

    #[Pesel()]
    public ?string $pesel = null;

    #[Nip()]
    public ?string $nip = null;

    // @todo validate
    #[Assert\Length(min: 26, max: 26)]
    public ?string $bankAccountNumber = null;

    #[Assert\Type('boolean')]
    public ?bool $allowsToSendPitByEmail = null;

    private ?int $id = null;

    #[Assert\Callback]
    public function validateDateOfBirth(
        ExecutionContextInterface $context,
        $payload
    )
    {
        if (!$this->dateOfBirth) {
            return;
        }

        if ($this->dateOfBirth > new \DateTime()) {
            $context->buildViolation('Data urodzenia nie może być z przyszłości.')
                ->atPath('dateOfBirth')
                ->addViolation();
        }
    }

    #[Assert\Callback]
    public function validateDateOfBirthWithPesel(
        ExecutionContextInterface $context,
        $payload
    )
    {
        if (!$this->dateOfBirth || !$this->pesel) {
            return;
        }

        $year      = (int) $this->dateOfBirth->format('Y');
        $yearShort = $this->dateOfBirth->format('y');
        $month     = $this->dateOfBirth->format('m');
        $day       = $this->dateOfBirth->format('d');

        if ($year < 1800 || $year > 2299) {
            $context->buildViolation('Pesel nie istnieje dla tej daty urodzenia.')
                ->atPath('pesel')
                ->addViolation();
        }

        if ($year >= 1800 && $year <= 1899) $month = $month + 80;
        if ($year >= 2000 && $year <= 2099) $month = $month + 20;
        if ($year >= 2100 && $year <= 2199) $month = $month + 40;
        if ($year >= 2200 && $year <= 2299) $month = $month + 60;

        $month = (string) $month;

        if (
            $yearShort !== \substr($this->pesel, 0, 2) ||
            $month !== \substr($this->pesel, 2, 2) ||
            $day !== \substr($this->pesel, 4, 2)
        ) {
            $context->buildViolation('Pesel nie zgadza się z datą urodzenia.')
                ->atPath('pesel')
                ->addViolation();
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }
}
