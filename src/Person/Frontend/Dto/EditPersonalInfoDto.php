<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Person\Frontend\Dto;

use DateTimeImmutable;
use Sudoku648\Meczyki\Person\Frontend\Validator\Constraints as PersonAssert;
use Sudoku648\Meczyki\Shared\Domain\ValueObject\Voivodeship;
use Sudoku648\Meczyki\Shared\Frontend\Validator\Constraints as SharedAssert;
use Symfony\Component\Validator\Constraints as Assert;

#[PersonAssert\PersonUnique]
#[PersonAssert\PersonDateOfBirth]
final class EditPersonalInfoDto
{
    public function __construct(
        #[Assert\Email]
        public ?string $email = null,
        public ?DateTimeImmutable $dateOfBirth = null,
        #[Assert\Length(min: 2, max: 100)]
        public ?string $placeOfBirth = null,
        #[Assert\Length(min: 2, max: 100)]
        public ?string $addressTown = null,
        #[Assert\Length(min: 2, max: 100)]
        public ?string $addressStreet = null,
        #[Assert\Length(min: 6, max: 6)]
        #[SharedAssert\PolishPostCode]
        public ?string $addressPostCode = null,
        #[Assert\Length(min: 2, max: 100)]
        public ?string $addressPostOffice = null,
        #[Assert\Type(type: Voivodeship::class)]
        public ?Voivodeship $addressVoivodeship = null,
        #[Assert\Length(min: 2, max: 100)]
        public ?string $addressCounty = null,
        #[Assert\Length(min: 2, max: 100)]
        public ?string $addressGmina = null,
        #[Assert\Length(min: 2, max: 150)]
        public ?string $taxOfficeName = null,
        #[Assert\Length(min: 2, max: 150)]
        public ?string $taxOfficeAddress = null,
        #[PersonAssert\Pesel]
        public ?string $pesel = null,
        #[SharedAssert\Nip]
        public ?string $nip = null,
        #[Assert\Iban]
        public ?string $iban = null,
        #[Assert\Type('boolean')]
        public ?bool $allowsToSendPitByEmail = null,
    ) {
    }
}
