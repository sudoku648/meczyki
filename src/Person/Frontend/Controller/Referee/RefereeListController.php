<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Person\Frontend\Controller\Referee;

use Sudoku648\Meczyki\Person\Domain\Entity\Person;
use Sudoku648\Meczyki\Person\Domain\Persistence\PersonRepositoryInterface;
use Sudoku648\Meczyki\Person\Frontend\DataTable\DataTableRefereeRow;
use Sudoku648\Meczyki\Person\Infrastructure\Persistence\Doctrine\DoctrinePersonRepository;
use Sudoku648\Meczyki\Person\Infrastructure\Persistence\PageView\PersonPageView;
use Sudoku648\Meczyki\Security\Infrastructure\Voter\PersonVoter;
use Sudoku648\Meczyki\Shared\Frontend\Controller\Traits\DataTableTrait;
use Sudoku648\Meczyki\Shared\Frontend\DataTable\DataTable;
use Sudoku648\Meczyki\Shared\Frontend\DataTable\Factory\DataTableParamsFactory;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RefereeListController extends RefereeAbstractController
{
    use DataTableTrait;

    public function list(): Response
    {
        $this->denyAccessUnlessGranted(PersonVoter::LIST);

        return $this->render('person/referee/list.html.twig');
    }

    public function fetch(
        PersonRepositoryInterface $repository,
        Request $request,
    ): JsonResponse {
        $this->denyAccessUnlessGranted(PersonVoter::LIST);

        $params = DataTableParamsFactory::fromRequest($request);

        $criteria                = new PersonPageView($params['page']);
        $criteria->sortColumn    = $params['order']['column'] ?? DoctrinePersonRepository::SORT_DEFAULT;
        $criteria->sortDirection = $params['order']['dir'] ?? DoctrinePersonRepository::SORT_DIR_DEFAULT;
        $criteria->perPage       = (int) $params['length'];

        $criteria->globalSearch  = $params['search'];

        $criteria->fullNameLike = $params['searches']['fullName'];

        $criteria->isReferee = true;

        $objects = $repository->findByCriteria($criteria);

        $rows = [];

        /** @var Person $person */
        foreach ($objects as $objKey => $person) {
            $rows[] = new DataTableRefereeRow(
                $this->getOrdinalNumberForDataTable($objKey, $criteria),
                $this->renderView(
                    'person/_datatable_checkbox.html.twig',
                    [
                        'personId' => $person->getId(),
                    ]
                ),
                $person->getFullName(),
                $this->getButtonsForDataTable($person)
            );
        }

        $countCriteria            = new PersonPageView();
        $countCriteria->isReferee = true;

        $dataTable = new DataTable(
            $params['draw'],
            $repository->countByCriteria($countCriteria),
            $repository->countByCriteria($criteria),
            $rows
        );

        return new JsonResponse($dataTable);
    }

    private function getButtonsForDataTable(Person $person): string
    {
        $buttons = '';

        if ($this->isGranted(PersonVoter::SHOW, $person)) {
            $buttons .= $this->renderView(
                'buttons/show.html.twig',
                [
                    'btn_size'   => 'table',
                    'path'       => 'person_single',
                    'parameters' => [
                        'person_id' => $person->getId(),
                    ],
                ]
            );
        }
        if ($this->isGranted(PersonVoter::EDIT, $person)) {
            $buttons .= $this->renderView(
                'buttons/edit.html.twig',
                [
                    'btn_size'   => 'table',
                    'path'       => 'person_edit',
                    'parameters' => [
                        'person_id' => $person->getId(),
                    ],
                ]
            );
        }
        if ($this->isGranted(PersonVoter::DELETE, $person)) {
            $buttons .= $this->renderView(
                'person/_delete_form.html.twig',
                [
                    'btn_size' => 'table',
                    'person'   => $person,
                ]
            );
        }

        return $buttons;
    }
}