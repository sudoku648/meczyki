<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Person\Frontend\Factory;

use Sudoku648\Meczyki\Person\Domain\Entity\Person;
use Sudoku648\Meczyki\Person\Frontend\Dto\EditPersonalInfoDto;
use Sudoku648\Meczyki\Person\Infrastructure\Mapper\MatchGameFunctionMapper;

readonly class EditPersonalInfoDtoFactory
{
    public function __construct(
        private MatchGameFunctionMapper $functionMapper,
    ) {
    }

    public function fromEntity(Person $person): EditPersonalInfoDto
    {
        return new EditPersonalInfoDto(
            $person->getEmail(),
            $person->getDateOfBirth(),
            $person->getPlaceOfBirth(),
            $person->getAddress()->getTown(),
            $person->getAddress()->getStreet(),
            $person->getAddress()->getPostCode(),
            $person->getAddress()->getPostOffice(),
            $person->getAddress()->getVoivodeship(),
            $person->getAddress()->getCounty(),
            $person->getAddress()->getGmina(),
            $person->getTaxOfficeName(),
            $person->getTaxOfficeAddress(),
            $person->getPesel()?->getValue(),
            $person->getNip()?->getValue(),
            $person->getIban()?->getPrefix() . $person->getIban()?->getNumber(),
            $person->allowsToSendPitByEmail(),
        );
    }
}
