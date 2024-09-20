<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\MatchGameBill\Domain\Model\Part;

final readonly class PersonData
{
    public function __construct(
        public string $function,
        public string $firstName,
        public string $lastName,
        public string $dateOfBirth,
        public string $placeOfBirth,
        public string $address,
        public string $voivodeship,
        public string $county,
        public string $gmina,
        public string $taxOffice,
        public bool $isPesel,
        public string $peselOrNip,
    ) {
    }
}
