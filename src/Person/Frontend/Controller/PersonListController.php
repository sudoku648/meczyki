<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Person\Frontend\Controller;

use Sudoku648\Meczyki\Person\Domain\ValueObject\MatchGameFunction;
use Sudoku648\Meczyki\Person\Frontend\DataTable\Provider\PersonDataTableProvider;
use Sudoku648\Meczyki\Security\Infrastructure\Voter\PersonVoter;
use Sudoku648\Meczyki\Shared\Frontend\Controller\AbstractController;
use Sudoku648\Meczyki\Shared\Frontend\Service\BreadcrumbBuilder;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class PersonListController extends AbstractController
{
    public function list(BreadcrumbBuilder $breadcrumbBuilder): Response
    {
        $this->denyAccessUnlessGranted(PersonVoter::LIST);

        $breadcrumbBuilder
            ->add('dashboard')
            ->add('people_list');

        return $this->render('person/list.html.twig');
    }

    public function listDelegates(BreadcrumbBuilder $breadcrumbBuilder): Response
    {
        $this->denyAccessUnlessGranted(PersonVoter::LIST);

        $breadcrumbBuilder
            ->add('dashboard')
            ->add('people_list')
            ->add('people_list_delegates');

        return $this->render(
            'person/list_single_function.html.twig',
            [
                'tableId' => 'delegates',
                'title'   => 'Delegates',
            ]
        );
    }

    public function listReferees(BreadcrumbBuilder $breadcrumbBuilder): Response
    {
        $this->denyAccessUnlessGranted(PersonVoter::LIST);

        $breadcrumbBuilder
            ->add('dashboard')
            ->add('people_list')
            ->add('people_list_referees');

        return $this->render(
            'person/list_single_function.html.twig',
            [
                'tableId' => 'referees',
                'title'   => 'Referees',
            ]
        );
    }

    public function listRefereeObservers(BreadcrumbBuilder $breadcrumbBuilder): Response
    {
        $this->denyAccessUnlessGranted(PersonVoter::LIST);

        $breadcrumbBuilder
            ->add('dashboard')
            ->add('people_list')
            ->add('people_list_referee_observers');

        return $this->render(
            'person/list_single_function.html.twig',
            [
                'tableId' => 'referee-observers',
                'title'   => 'Referee observers',
            ]
        );
    }

    public function fetch(PersonDataTableProvider $dataTableProvider): JsonResponse
    {
        return new JsonResponse($dataTableProvider->provide());
    }

    public function fetchWithFunction(
        MatchGameFunction $function,
        PersonDataTableProvider $dataTableProvider
    ): JsonResponse {
        return new JsonResponse($dataTableProvider->provide($function));
    }
}
