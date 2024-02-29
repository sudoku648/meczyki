<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Team\Frontend\DataTable\Factory;

use Sudoku648\Meczyki\Security\Infrastructure\Voter\TeamVoter;
use Sudoku648\Meczyki\Shared\Frontend\DataTable\Traits\DataTableOrdinalNumberTrait;
use Sudoku648\Meczyki\Shared\Infrastructure\Persistence\Criteria;
use Sudoku648\Meczyki\Team\Domain\Entity\Team;
use Sudoku648\Meczyki\Team\Domain\Persistence\TeamRepositoryInterface;
use Sudoku648\Meczyki\Team\Frontend\DataTable\Model\DataTableTeamRow;
use Symfony\Bundle\SecurityBundle\Security;
use Twig\Environment;

use function array_keys;
use function array_map;
use function array_values;

readonly class DataTableTeamResponseFactory
{
    use DataTableOrdinalNumberTrait;

    public function __construct(
        private Environment $twig,
        private Security $security,
        private TeamRepositoryInterface $repository,
    ) {
    }

    public function fromCriteria(Criteria $criteria): array
    {
        $objects = $this->repository->findByCriteria($criteria);

        return array_map(
            function (int $objKey, Team $team) use ($criteria) {
                return new DataTableTeamRow(
                    $this->getOrdinalNumber($objKey, $criteria),
                    $this->twig->render(
                        'team/_datatable_checkbox.html.twig',
                        [
                            'teamId' => $team->getId(),
                        ]
                    ),
                    $team->getName()->getValue(),
                    $team->getClub()->getName()->getValue(),
                    $this->getButtonsForDataTable($team)
                );
            },
            array_keys($objects),
            array_values($objects)
        );
    }

    private function getButtonsForDataTable(Team $team): string
    {
        $buttons = '';

        if ($this->security->isGranted(TeamVoter::SHOW, $team)) {
            $buttons .= $this->twig->render(
                'buttons/show.html.twig',
                [
                    'btn_size'   => 'table',
                    'path'       => 'team_single',
                    'parameters' => [
                        'club_id' => $team->getClub()->getId(),
                        'team_id' => $team->getId(),
                    ],
                ]
            );
        }
        if ($this->security->isGranted(TeamVoter::EDIT, $team)) {
            $buttons .= $this->twig->render(
                'buttons/edit.html.twig',
                [
                    'btn_size'   => 'table',
                    'path'       => 'team_edit',
                    'parameters' => [
                        'club_id' => $team->getClub()->getId(),
                        'team_id' => $team->getId(),
                    ],
                ]
            );
        }
        if ($this->security->isGranted(TeamVoter::DELETE, $team)) {
            $buttons .= $this->twig->render(
                'team/_delete_form.html.twig',
                [
                    'btn_size' => 'table',
                    'club_id'  => $team->getClub()->getId(),
                    'team'     => $team,
                ]
            );
        }

        return $buttons;
    }
}
