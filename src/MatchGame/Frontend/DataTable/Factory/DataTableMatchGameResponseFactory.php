<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\MatchGame\Frontend\DataTable\Factory;

use Sudoku648\Meczyki\MatchGame\Domain\Entity\MatchGame;
use Sudoku648\Meczyki\MatchGame\Domain\Persistence\MatchGameRepositoryInterface;
use Sudoku648\Meczyki\MatchGame\Frontend\DataTable\Model\DataTableMatchGameRow;
use Sudoku648\Meczyki\Security\Infrastructure\Voter\MatchGameVoter;
use Sudoku648\Meczyki\Shared\Frontend\DataTable\Traits\DataTableOrdinalNumberTrait;
use Sudoku648\Meczyki\Shared\Infrastructure\Persistence\Criteria;
use Symfony\Bundle\SecurityBundle\Security;
use Twig\Environment;

use function array_keys;
use function array_map;
use function array_values;

readonly class DataTableMatchGameResponseFactory
{
    use DataTableOrdinalNumberTrait;

    public function __construct(
        private Environment $twig,
        private Security $security,
        private MatchGameRepositoryInterface $repository,
    ) {
    }

    public function fromCriteria(Criteria $criteria): array
    {
        $objects = $this->repository->findByCriteria($criteria);

        return array_map(
            function (int $objKey, MatchGame $matchGame) use ($criteria) {
                return new DataTableMatchGameRow(
                    $this->getOrdinalNumber($objKey, $criteria),
                    $this->twig->render(
                        'match_game/_datatable_checkbox.html.twig',
                        [
                            'matchGameId' => $matchGame->getId(),
                        ]
                    ),
                    $matchGame->getDateTime()->format('d.m.Y H:i'),
                    $matchGame->getGameType()?->getName()->getValue() ?? '',
                    $this->twig->render(
                        'match_game/_datatable_competitors.html.twig',
                        [
                            'homeTeam' => $matchGame->getHomeTeam(),
                            'awayTeam' => $matchGame->getAwayTeam(),
                        ]
                    ),
                    $this->getButtonsForDataTable($matchGame)
                );
            },
            array_keys($objects),
            array_values($objects)
        );
    }

    private function getButtonsForDataTable(MatchGame $matchGame): string
    {
        $buttons = '';

        if ($this->security->isGranted(MatchGameVoter::SHOW, $matchGame)) {
            $buttons .= $this->twig->render(
                'match_game/_datatable/show.html.twig',
                [
                    'matchGameId' => $matchGame->getId(),
                ]
            );
        }
        if ($this->security->isGranted(MatchGameVoter::EDIT, $matchGame)) {
            $buttons .= $this->twig->render(
                'match_game/_datatable/edit.html.twig',
                [
                    'matchGameId' => $matchGame->getId(),
                ]
            );
        }
        if ($this->security->isGranted(MatchGameVoter::DELETE, $matchGame)) {
            $buttons .= $this->twig->render(
                'match_game/_datatable/_delete_form.html.twig',
                [
                    'matchGame' => $matchGame,
                ]
            );
        }

        return $buttons;
    }
}
