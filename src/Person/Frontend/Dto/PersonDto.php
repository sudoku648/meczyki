<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Person\Frontend\Dto;

use DateTime;
use DateTimeImmutable;
use Sudoku648\Meczyki\Person\Domain\ValueObject\PersonId;
use Sudoku648\Meczyki\Person\Frontend\Validator\Constraints\PersonUnique;
use Sudoku648\Meczyki\Person\Frontend\Validator\Constraints\Pesel;
use Sudoku648\Meczyki\Shared\Domain\ValueObject\Voivodeship;
use Sudoku648\Meczyki\Shared\Frontend\Validator\Constraints\Nip;
use Sudoku648\Meczyki\Shared\Frontend\Validator\Constraints\PolishMobilePhone;
use Sudoku648\Meczyki\Shared\Frontend\Validator\Constraints\PolishPostCode;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

use function substr;

#[PersonUnique]
class PersonDto
{
    private ?PersonId $id = null;

    #[Assert\NotBlank()]
    #[Assert\Length(min: 2, max: 100)]
    public ?string $firstName = null;

    #[Assert\NotBlank()]
    #[Assert\Length(min: 2, max: 100)]
    public ?string $lastName = null;

    public ?string $fullName = null;

    #[Assert\Length(min: 12, max: 12)]
    #[PolishMobilePhone()]
    public ?string $mobilePhone = null;

    public array $functions = [];

    #[Assert\Email()]
    public ?string $email = null;

    public ?DateTimeImmutable $dateOfBirth = null;

    #[Assert\Length(min: 2, max: 100)]
    public ?string $placeOfBirth = null;

    #[Assert\Length(min: 2, max: 100)]
    public ?string $addressTown = null;

    #[Assert\Length(min: 2, max: 100)]
    public ?string $addressStreet = null;

    #[Assert\Length(min: 6, max: 6)]
    #[PolishPostCode()]
    public ?string $addressPostCode = null;

    #[Assert\Length(min: 2, max: 100)]
    public ?string $addressPostOffice = null;

    #[Assert\Type(type: Voivodeship::class)]
    public ?Voivodeship $addressVoivodeship = null;

    #[Assert\Length(min: 2, max: 100)]
    public ?string $addressCounty = null;

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

    #[Assert\Iban()]
    public ?string $iban = null;

    #[Assert\Type('boolean')]
    public ?bool $allowsToSendPitByEmail = null;

    #[Assert\Callback]
    public function validateDateOfBirth(
        ExecutionContextInterface $context,
        $payload
    ) {
        if (!$this->dateOfBirth) {
            return;
        }

        if ($this->dateOfBirth > new DateTime()) {
            $context->buildViolation('Date of birth cannot be from the future.')
                ->atPath('dateOfBirth')
                ->setTranslationDomain('Person')
                ->addViolation();
        }
    }

    #[Assert\Callback]
    public function validateDateOfBirthWithPesel(
        ExecutionContextInterface $context,
        $payload
    ) {
        if (!$this->dateOfBirth || !$this->pesel) {
            return;
        }

        $year      = (int) $this->dateOfBirth->format('Y');
        $yearShort = $this->dateOfBirth->format('y');
        $month     = $this->dateOfBirth->format('m');
        $day       = $this->dateOfBirth->format('d');

        if ($year < 1800 || $year > 2299) {
            $context->buildViolation('PESEL doesn\'t exist for this date of birth.')
                ->atPath('pesel')
                ->setTranslationDomain('Person')
                ->addViolation();

            return;
        }

        if ($year >= 1800 && $year <= 1899) {
            $month = $month + 80;
        }
        if ($year >= 2000 && $year <= 2099) {
            $month = $month + 20;
        }
        if ($year >= 2100 && $year <= 2199) {
            $month = $month + 40;
        }
        if ($year >= 2200 && $year <= 2299) {
            $month = $month + 60;
        }

        $month = (string) $month;

        if (
            $yearShort !== substr($this->pesel, 0, 2) ||
            $month !== substr($this->pesel, 2, 2) ||
            $day !== substr($this->pesel, 4, 2)
        ) {
            $context->buildViolation('PESEL doesn\'t match with date of birth.')
                ->atPath('pesel')
                ->setTranslationDomain('Person')
                ->addViolation();
        }
    }

    public function getId(): ?PersonId
    {
        return $this->id;
    }

    public function setId(PersonId $id): void
    {
        $this->id = $id;
    }
}
