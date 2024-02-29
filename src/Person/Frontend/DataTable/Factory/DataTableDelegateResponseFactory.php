<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Person\Frontend\DataTable\Factory;

use Sudoku648\Meczyki\Person\Domain\Entity\Person;
use Sudoku648\Meczyki\Person\Domain\Persistence\PersonRepositoryInterface;
use Sudoku648\Meczyki\Person\Frontend\DataTable\Model\DataTableDelegateRow;
use Sudoku648\Meczyki\Security\Infrastructure\Voter\PersonVoter;
use Sudoku648\Meczyki\Shared\Frontend\DataTable\Traits\DataTableOrdinalNumberTrait;
use Sudoku648\Meczyki\Shared\Infrastructure\Persistence\Criteria;
use Symfony\Bundle\SecurityBundle\Security;
use Twig\Environment;

use function array_keys;
use function array_map;
use function array_values;

readonly class DataTableDelegateResponseFactory
{
    use DataTableOrdinalNumberTrait;

    public function __construct(
        private Environment $twig,
        private Security $security,
        private PersonRepositoryInterface $repository,
    ) {
    }

    public function fromCriteria(Criteria $criteria): array
    {
        $objects = $this->repository->findByCriteria($criteria);

        return array_map(
            function (int $objKey, Person $person) use ($criteria) {
                return new DataTableDelegateRow(
                    $this->getOrdinalNumber($objKey, $criteria),
                    $this->twig->render(
                        'person/_datatable_checkbox.html.twig',
                        [
                            'personId' => $person->getId(),
                        ]
                    ),
                    $person->getFullName(),
                    $this->getButtonsForDataTable($person)
                );
            },
            array_keys($objects),
            array_values($objects)
        );
    }

    private function getButtonsForDataTable(Person $person): string
    {
        $buttons = '';

        if ($this->security->isGranted(PersonVoter::SHOW, $person)) {
            $buttons .= $this->twig->render(
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
        if ($this->security->isGranted(PersonVoter::EDIT, $person)) {
            $buttons .= $this->twig->render(
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
        if ($this->security->isGranted(PersonVoter::DELETE, $person)) {
            $buttons .= $this->twig->render(
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
