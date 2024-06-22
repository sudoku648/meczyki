<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Person\Frontend\Form\Option;

use Sudoku648\Meczyki\Person\Domain\Entity\Person;
use Sudoku648\Meczyki\Person\Domain\Persistence\PersonRepositoryInterface;
use Sudoku648\Meczyki\Person\Domain\ValueObject\MatchGameFunction;

class PersonOption
{
    public static function default(
        PersonRepositoryInterface $personRepository,
        ?MatchGameFunction $matchGameFunction = null
    ): array {
        return [
            'choice_label' => function (Person $person) {
                return $person->getFullName();
            },
            'choices'      => $personRepository->allOrderedByName($matchGameFunction),
            'class'        => Person::class,
        ];
    }
}
