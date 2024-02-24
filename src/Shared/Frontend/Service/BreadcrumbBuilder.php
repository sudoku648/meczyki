<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Shared\Frontend\Service;

use Symfony\Component\Routing\RouterInterface;
use WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs;

readonly class BreadcrumbBuilder
{
    private const MAP = [
        'clubs_list'             => 'Kluby',
        'club_create'            => 'Dodaj klub',
        'club_edit'              => 'Edytuj klub',
        'club_single'            => 'Klub',
        'dashboard'              => 'Panel',
        'game_types_list'        => 'Typy rozgrywek',
        'game_type_create'       => 'Dodaj typ rozgrywek',
        'game_type_edit'         => 'Edytuj typ rozgrywek',
        'game_type_single'       => 'Typ rozgrywek',
        'match_games_list'       => 'Mecze',
        'match_game_create'      => 'Dodaj mecz',
        'match_game_edit'        => 'Edytuj mecz',
        'match_game_single'      => 'Mecz',
        'match_game_bill_create' => 'Dodaj rachunek',
        'match_game_bill_edit'   => 'Edytuj rachunek',
        'match_game_bill_single' => 'Rachunek',
    ];

    public function __construct(
        private RouterInterface $router,
        private Breadcrumbs $breadcrumbs,
    ) {
    }

    public function add(string $route, array $params = []): self
    {
        $this->breadcrumbs->addItem(
            self::MAP[$route],
            $this->router->generate($route, $params)
        );

        return $this;
    }
}
