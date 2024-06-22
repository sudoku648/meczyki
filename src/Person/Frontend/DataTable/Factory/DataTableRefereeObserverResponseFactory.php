<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Person\Frontend\DataTable\Factory;

use Sudoku648\Meczyki\Person\Domain\Entity\Person;
use Sudoku648\Meczyki\Person\Frontend\DataTable\Model\DataTableRefereeObserverRow;
use Sudoku648\Meczyki\Shared\Infrastructure\Persistence\Criteria;

use function array_keys;
use function array_map;
use function array_values;

readonly class DataTableRefereeObserverResponseFactory extends DataTablePersonResponseFactory
{
    public function fromCriteria(Criteria $criteria): array
    {
        $objects = $this->repository->findByCriteria($criteria);

        return array_map(
            function (int $objKey, Person $person) use ($criteria) {
                return new DataTableRefereeObserverRow(
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
}
