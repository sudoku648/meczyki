<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Person\Frontend\Factory;

use Sudoku648\Meczyki\Person\Domain\Entity\Person;
use Sudoku648\Meczyki\Person\Frontend\Dto\UpdatePersonDto;
use Sudoku648\Meczyki\Person\Infrastructure\Mapper\MatchGameFunctionMapper;

readonly class UpdatePersonDtoFactory
{
    public function __construct(
        private MatchGameFunctionMapper $functionMapper,
    ) {
    }

    public function fromEntity(Person $person): UpdatePersonDto
    {
        return new UpdatePersonDto(
            $person->getId(),
            $person->getFirstName()->getValue(),
            $person->getLastName()->getValue(),
            $person->getMobilePhone()?->getValue(),
            $this->functionMapper->mapValuesToEnums($person->getFunctions()),
        );
    }
}
