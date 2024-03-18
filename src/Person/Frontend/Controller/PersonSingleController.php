<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Person\Frontend\Controller;

use Sudoku648\Meczyki\Person\Domain\Entity\Person;
use Sudoku648\Meczyki\Security\Infrastructure\Voter\PersonVoter;
use Sudoku648\Meczyki\Shared\Frontend\Controller\AbstractController;
use Sudoku648\Meczyki\Shared\Frontend\Service\BreadcrumbBuilder;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\HttpFoundation\Response;

class PersonSingleController extends AbstractController
{
    public function __construct(
        private readonly BreadcrumbBuilder $breadcrumbBuilder,
    ) {
    }

    public function __invoke(
        #[MapEntity(mapping: ['person_id' => 'id'])] Person $person,
    ): Response {
        $this->denyAccessUnlessGranted(PersonVoter::SHOW, $person);

        $this->breadcrumbBuilder
            ->add('dashboard')
            ->add('people_list')
            ->add('person_single', ['person_id' => $person->getId()]);

        return $this->render(
            'person/single.html.twig',
            [
                'person' => $person,
            ]
        );
    }
}
