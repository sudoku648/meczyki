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
        $choices = [];

        $people = $personRepository->allOrderedByName($personType);
        $userPerson = $user->getPerson();

        if ($userPerson && \in_array($userPerson, $people)) {
            $choices['Ty'] = [$userPerson];
        }

        foreach ($people as $person) {
            if ($person === $userPerson) continue;

            $choices[] = $person;
        }

        return [
            'choice_label' => function (Person $person) {
                return $person->getFullName();
            },
            'choices'      => $choices,
            'class'        => Person::class,
        ];
    }
}
