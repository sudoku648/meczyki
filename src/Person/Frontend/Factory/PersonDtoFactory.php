<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Person\Frontend\Factory;

use Sudoku648\Meczyki\Person\Domain\Entity\Person;
use Sudoku648\Meczyki\Person\Frontend\Dto\PersonDto;
use Sudoku648\Meczyki\Person\Infrastructure\Mapper\MatchGameFunctionMapper;

readonly class PersonDtoFactory
{
    public function __construct(
        private MatchGameFunctionMapper $functionMapper,
    ) {
    }

    public function fromEntity(Person $person): PersonDto
    {
        return new PersonDto(
            $person->getId(),
            $person->getFirstName()->getValue(),
            $person->getLastName()->getValue(),
            $person->getMobilePhone()?->getValue(),
            $this->functionMapper->mapValuesToEnums($person->getFunctions()),
        );
    }
}
