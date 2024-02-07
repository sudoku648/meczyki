<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Person\Frontend\Controller;

use Sudoku648\Meczyki\Person\Domain\Entity\Person;
use Sudoku648\Meczyki\Person\Domain\Event\PersonHasBeenSeenEvent;
use Sudoku648\Meczyki\Security\Infrastructure\Voter\PersonVoter;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\HttpFoundation\Response;

class PersonSingleController extends PersonAbstractController
{
    public function __invoke(
        #[MapEntity(mapping: ['person_id' => 'id'])] Person $person,
    ): Response {
        $this->denyAccessUnlessGranted(PersonVoter::SHOW, $person);

        $this->breadcrumbs->addItem(
            $person->getFullName(),
            $this->router->generate(
                'person_single',
                [
                    'person_id' => $person->getId(),
                ]
            )
        );

        $this->dispatcher->dispatch((new PersonHasBeenSeenEvent($person)));

        return $this->render(
            'person/single.html.twig',
            [
                'person' => $person,
            ]
        );
    }
}
