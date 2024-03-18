<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Shared\Frontend\Service;

use Symfony\Component\Routing\RouterInterface;
use WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs;

readonly class BreadcrumbBuilder
{
    private const MAP = [
        'clubs_list'                    => 'Clubs',
        'club_create'                   => 'Add club',
        'club_edit'                     => 'Edit club',
        'club_single'                   => 'Club',
        'dashboard'                     => 'Dashboard',
        'game_types_list'               => 'Game types',
        'game_type_create'              => 'Add game type',
        'game_type_edit'                => 'Edit game type',
        'game_type_single'              => 'Game type',
        'match_games_list'              => 'Matches',
        'match_game_create'             => 'Add match',
        'match_game_edit'               => 'Edit match',
        'match_game_single'             => 'Match',
        'match_game_bill_create'        => 'Add bill',
        'match_game_bill_edit'          => 'Edit bill',
        'match_game_bill_single'        => 'Bill',
        'people_list'                   => 'People',
        'people_list_delegates'         => 'Delegates',
        'people_list_referees'          => 'Referees',
        'people_list_referee_observers' => 'Referee observers',
        'person_create'                 => 'Add person',
        'person_edit'                   => 'Edit person',
        'person_single'                 => 'Person',
        'person_personal_info_edit'     => 'Edit personal info',
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
