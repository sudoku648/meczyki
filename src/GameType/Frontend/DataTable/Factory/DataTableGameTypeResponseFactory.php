<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\GameType\Frontend\DataTable\Factory;

use Sudoku648\Meczyki\GameType\Domain\Entity\GameType;
use Sudoku648\Meczyki\GameType\Domain\Persistence\GameTypeRepositoryInterface;
use Sudoku648\Meczyki\GameType\Frontend\DataTable\Model\DataTableGameTypeRow;
use Sudoku648\Meczyki\Security\Infrastructure\Voter\GameTypeVoter;
use Sudoku648\Meczyki\Shared\Frontend\DataTable\Traits\DataTableOrdinalNumberTrait;
use Sudoku648\Meczyki\Shared\Infrastructure\Persistence\Criteria;
use Symfony\Bundle\SecurityBundle\Security;
use Twig\Environment;

use function array_keys;
use function array_map;
use function array_values;

readonly class DataTableGameTypeResponseFactory
{
    use DataTableOrdinalNumberTrait;

    public function __construct(
        private Environment $twig,
        private Security $security,
        private GameTypeRepositoryInterface $repository,
    ) {
    }

    public function fromCriteria(Criteria $criteria): array
    {
        $objects = $this->repository->findByCriteria($criteria);

        return array_map(
            function (int $objKey, GameType $gameType) use ($criteria) {
                return new DataTableGameTypeRow(
                    $this->getOrdinalNumber($objKey, $criteria),
                    $this->twig->render(
                        'game_type/_datatable_checkbox.html.twig',
                        [
                            'gameTypeId' => $gameType->getId(),
                        ]
                    ),
                    $gameType->getName()->getValue(),
                    $this->getButtonsForDataTable($gameType)
                );
            },
            array_keys($objects),
            array_values($objects)
        );
    }

    private function getButtonsForDataTable(GameType $gameType): string
    {
        $buttons = '';

        if ($this->security->isGranted(GameTypeVoter::SHOW, $gameType)) {
            $buttons .= $this->twig->render(
                'buttons/show.html.twig',
                [
                    'btn_size'   => 'table',
                    'path'       => 'game_type_single',
                    'parameters' => [
                        'game_type_id' => $gameType->getId(),
                    ],
                ]
            );
        }
        if ($this->security->isGranted(GameTypeVoter::EDIT, $gameType)) {
            $buttons .= $this->twig->render(
                'buttons/edit.html.twig',
                [
                    'btn_size'   => 'table',
                    'path'       => 'game_type_edit',
                    'parameters' => [
                        'game_type_id' => $gameType->getId(),
                    ],
                ]
            );
        }
        if ($this->security->isGranted(GameTypeVoter::DELETE, $gameType)) {
            $buttons .= $this->twig->render(
                'game_type/_delete_form.html.twig',
                [
                    'btn_size' => 'table',
                    'gameType' => $gameType,
                ]
            );
        }

        return $buttons;
    }
}
