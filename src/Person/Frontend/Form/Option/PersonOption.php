<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Person\Frontend\Form\Option;

use Sudoku648\Meczyki\Person\Domain\Entity\Person;
use Sudoku648\Meczyki\Person\Domain\Persistence\PersonRepositoryInterface;

class PersonOption
{
    public static function default(
        PersonRepositoryInterface $personRepository,
        $personType = null
    ): array {
        return [
            'choice_label' => function (Person $person) {
                return $person->getFullName();
            },
            'choices'      => $personRepository->allOrderedByName($personType),
            'class'        => Person::class,
        ];
    }
}
