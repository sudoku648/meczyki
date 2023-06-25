<?php

declare(strict_types=1);

namespace App\Controller\Person\Referee;

use App\Controller\Traits\DataTableTrait;
use App\DataTable\DataTable;
use App\DataTable\DataTableRefereeRow;
use App\Entity\Person;
use App\PageView\PersonPageView;
use App\Repository\PersonRepository;
use App\Security\Voter\PersonVoter;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RefereeFrontController extends RefereeAbstractController
{
    use DataTableTrait;

    public function front(): Response
    {
        $this->denyAccessUnlessGranted(PersonVoter::LIST);

        return $this->render('person/referee/index.html.twig');
    }

    public function fetch(
        PersonRepository $repository,
        Request $request
    ): JsonResponse {
        $this->denyAccessUnlessGranted(PersonVoter::LIST);

        $params = $this->prepareDataTableAjaxRequest($request);

        $criteria                = new PersonPageView($params['page']);
        $criteria->sortColumn    = $params['order']['column'] ?? PersonRepository::SORT_DEFAULT;
        $criteria->sortDirection = $params['order']['dir'] ?? PersonRepository::SORT_DIR_DEFAULT;
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
