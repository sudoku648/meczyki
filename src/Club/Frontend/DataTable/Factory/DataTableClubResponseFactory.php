<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Club\Frontend\DataTable\Factory;

use Sudoku648\Meczyki\Club\Domain\Entity\Club;
use Sudoku648\Meczyki\Club\Domain\Persistence\ClubRepositoryInterface;
use Sudoku648\Meczyki\Club\Frontend\DataTable\Model\DataTableClubRow;
use Sudoku648\Meczyki\Security\Infrastructure\Voter\ClubVoter;
use Sudoku648\Meczyki\Shared\Frontend\DataTable\Traits\DataTableOrdinalNumberTrait;
use Sudoku648\Meczyki\Shared\Infrastructure\Persistence\Criteria;
use Symfony\Bundle\SecurityBundle\Security;
use Twig\Environment;

use function array_keys;
use function array_map;
use function array_values;

readonly class DataTableClubResponseFactory
{
    use DataTableOrdinalNumberTrait;

    public function __construct(
        private Environment $twig,
        private Security $security,
        private ClubRepositoryInterface $repository,
    ) {
    }

    public function fromCriteria(Criteria $criteria): array
    {
        $objects = $this->repository->findByCriteria($criteria);

        return array_map(
            function (int $objKey, Club $club) use ($criteria) {
                return new DataTableClubRow(
                    $this->getOrdinalNumber($objKey, $criteria),
                    $this->twig->render(
                        'club/_datatable_checkbox.html.twig',
                        [
                            'clubId' => $club->getId(),
                        ]
                    ),
                    $club->getName()->getValue(),
                    $this->getButtonsForDataTable($club)
                );
            },
            array_keys($objects),
            array_values($objects)
        );
    }

    private function getButtonsForDataTable(Club $club): string
    {
        $buttons = '';

        if ($this->security->isGranted(ClubVoter::SHOW, $club)) {
            $buttons .= $this->twig->render(
                'buttons/show.html.twig',
                [
                    'btn_size'   => 'table',
                    'path'       => 'club_single',
                    'parameters' => [
                        'club_id' => $club->getId(),
                    ],
                ]
            );
        }
        if ($this->security->isGranted(ClubVoter::EDIT, $club)) {
            $buttons .= $this->twig->render(
                'buttons/edit.html.twig',
                [
                    'btn_size'   => 'table',
                    'path'       => 'club_edit',
                    'parameters' => [
                        'club_id' => $club->getId(),
                    ],
                ]
            );
        }
        if ($this->security->isGranted(ClubVoter::DELETE, $club)) {
            $buttons .= $this->twig->render(
                'club/_delete_form.html.twig',
                [
                    'btn_size' => 'table',
                    'club'     => $club,
                ]
            );
        }

        return $buttons;
    }
}
