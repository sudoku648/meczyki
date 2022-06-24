<?php

declare(strict_types=1);

namespace App\Form\Option;

use App\Entity\Person;
use App\Repository\PersonRepository;

class PersonOption
{
    public static function default(
        PersonRepository $personRepository,
        $personType = null
    ): array
    {
        return [
            'choice_label' => function (Person $person) {
                return $person->getFullName();
            },
            'choices'      => $personRepository->allOrderedByName($personType),
            'class'        => Person::class,
        ];
    }
}
