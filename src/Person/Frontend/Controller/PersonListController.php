<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Person\Frontend\Controller;

use Sudoku648\Meczyki\Person\Domain\Entity\Person;
use Sudoku648\Meczyki\Person\Domain\Persistence\PersonRepositoryInterface;
use Sudoku648\Meczyki\Person\Frontend\DataTable\DataTablePersonRow;
use Sudoku648\Meczyki\Person\Infrastructure\Persistence\Doctrine\DoctrinePersonRepository;
use Sudoku648\Meczyki\Person\Infrastructure\Persistence\PageView\PersonPageView;
use Sudoku648\Meczyki\Security\Infrastructure\Voter\PersonVoter;
use Sudoku648\Meczyki\Shared\Frontend\Controller\Traits\DataTableTrait;
use Sudoku648\Meczyki\Shared\Frontend\DataTable\DataTable;
use Sudoku648\Meczyki\Shared\Frontend\DataTable\Factory\DataTableParamsFactory;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use function implode;

class PersonListController extends PersonAbstractController
{
    use DataTableTrait;

    public function list(): Response
    {
        $this->denyAccessUnlessGranted(PersonVoter::LIST);

        return $this->render('person/list.html.twig');
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

        $objects = $repository->findByCriteria($criteria);

        $rows = [];

        /** @var Person $person */
        foreach ($objects as $objKey => $person) {
            $functions = [];
            if ($person->isDelegate()) {
                $functions[] = 'delegat';
            }
            if ($person->isReferee()) {
                $functions[] = 'sędzia';
            }
            if ($person->isRefereeObserver()) {
                $functions[] = 'obserwator';
            }

            $rows[] = new DataTablePersonRow(
                $this->getOrdinalNumberForDataTable($objKey, $criteria),
                $this->renderView(
                    'person/_datatable_checkbox.html.twig',
                    [
                        'personId' => $person->getId(),
                    ]
                ),
                $person->getFullName(),
                implode(', ', $functions),
                $this->getButtonsForDataTable($person)
            );
        }

        $dataTable = new DataTable(
            $params['draw'],
            $repository->getTotalCount(),
            $repository->countByCriteria($criteria),
            $rows,
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
