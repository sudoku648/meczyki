<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Shared\Frontend\Service;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Sudoku648\Meczyki\Security\Infrastructure\Voter\ClubVoter;
use Sudoku648\Meczyki\Security\Infrastructure\Voter\DashboardVoter;
use Sudoku648\Meczyki\Security\Infrastructure\Voter\GameTypeVoter;
use Sudoku648\Meczyki\Security\Infrastructure\Voter\MatchGameVoter;
use Sudoku648\Meczyki\Security\Infrastructure\Voter\PersonVoter;
use Sudoku648\Meczyki\Security\Infrastructure\Voter\TeamVoter;
use Sudoku648\Meczyki\Security\Infrastructure\Voter\UserVoter;
use Symfony\Bundle\SecurityBundle\Security;

readonly class MenuBuilder
{
    public function __construct(
        private FactoryInterface $factory,
        private Security $security,
    ) {
    }

    public function createMainMenu(array $options): ItemInterface
    {
        $menu = $this->factory->createItem('root');

        if ($this->security->isGranted(DashboardVoter::DASHBOARD)) {
            $menu->addChild(
                'panel',
                [
                    'route' => 'dashboard',
                    'label' => 'Panel',
                ]
            );
        }
        if ($this->security->isGranted(UserVoter::LIST)) {
            $menu->addChild(
                'users',
                [
                    'route' => 'users_list',
                    'label' => 'Użytkownicy',
                ]
            );
        }
        if ($this->security->isGranted(PersonVoter::LIST)) {
            $menu->addChild(
                'people',
                [
                    'route' => 'people_list',
                    'label' => 'Osoby',
                ]
            );
        }
        if ($this->security->isGranted(GameTypeVoter::LIST)) {
            $menu->addChild(
                'gameTypes',
                [
                    'route' => 'game_types_list',
                    'label' => 'Typy rozgrywek',
                ]
            );
        }
        if ($this->security->isGranted(ClubVoter::LIST)) {
            $menu->addChild(
                'clubs',
                [
                    'route' => 'clubs_list',
                    'label' => 'Kluby',
                ]
            );
        }
        if ($this->security->isGranted(TeamVoter::LIST)) {
            $menu->addChild(
                'teams',
                [
                    'route' => 'teams_list',
                    'label' => 'Drużyny',
                ]
            );
        }
        if ($this->security->isGranted(MatchGameVoter::LIST)) {
            $menu->addChild(
                'matchGames',
                [
                    'route' => 'match_games_list',
                    'label' => 'Mecze',
                ]
            );
        }

        return $menu;
    }

    public function createTopMenu(array $options): ItemInterface
    {
        $menu = $this->factory->createItem('root');

        $menu
            ->addChild(
                'rt',
                [
                    'label' => '',
                ]
            )
            ->setExtras(
                [
                    'icon' => 'fas fa-user fa-fw',
                ]
            )
        ;

        if ($this->security->isGranted('IS_IMPERSONATOR')) {
            $menu['rt']
                ->addChild(
                    'exit_impersonator',
                    [
                        'route'           => 'users_list',
                        'routeParameters' => [
                            '_switch_user' => '_exit',
                        ],
                        'label' => 'Wróć na swoje konto',
                    ]
                )
            ;
        }

        $menu['rt']
            ->addChild(
                'profile',
                [
                    'route' => 'user_profile',
                    'label' => 'Profil',
                ]
            )
        ;

        if ($this->security->isGranted(PersonVoter::EDIT_PERSONAL_INFO)) {
            $menu['rt']
                ->addChild(
                    'personalInfo',
                    [
                        'route' => 'person_personal_info_edit',
                        'label' => 'Dane osobowe',
                    ]
                )
            ;
        }

        $menu['rt']
            ->addChild(
                'logout',
                [
                    'route' => 'app_logout',
                    'label' => 'Wyloguj',
                ]
            )
        ;

        return $menu;
    }
}
