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
        'match_games_list'              => 'Match games',
        'match_game_create'             => 'Add match game',
        'match_game_edit'               => 'Edit match game',
        'match_game_single'             => 'Match game',
        'match_game_bill_create'        => 'Add match game bill',
        'match_game_bill_edit'          => 'Edit match game bill',
        'match_game_bill_single'        => 'Match game bill',
        'people_list'                   => 'People',
        'people_list_delegates'         => 'Delegates',
        'people_list_referees'          => 'Referees',
        'people_list_referee_observers' => 'Referee observers',
        'person_create'                 => 'Add person',
        'person_edit'                   => 'Edit person',
        'person_single'                 => 'Person',
        'person_personal_info_edit'     => 'Edit personal info',
        'teams_list'                    => 'Teams',
        'team_create'                   => 'Add team',
        'team_edit'                     => 'Edit team',
        'team_single'                   => 'Team',
        'users_list'                    => 'Users',
        'user_create'                   => 'Add user',
        'user_edit'                     => 'Edit user',
        'user_single'                   => 'User',
        'user_bind_with_person'         => 'Bind with person',
        'user_profile'                  => 'Profile',
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
