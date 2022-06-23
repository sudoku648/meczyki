<?php

declare(strict_types=1);

namespace App\Form\Option;

use App\Entity\Person;
use App\Entity\User;
use App\Repository\PersonRepository;

class PersonOption
{
    public static function default(
        PersonRepository $personRepository,
        User $user,
        $personType = null
    ): array
    {
        return [
            'choice_label'      => function (Person $person) {
                return $person->getFullName();
            },
            'choices'           => $personRepository->allOrderedByName($personType),
            'class'             => Person::class,
            'preferred_choices' => function(Person $person) use ($user) {
                return $person === $user->getPerson();
            },
        ];
    }
}
