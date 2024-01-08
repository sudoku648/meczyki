<?php

declare(strict_types=1);

namespace App\Service;

use App\Security\Voter\ClubVoter;
use App\Security\Voter\FrontVoter;
use App\Security\Voter\GameTypeVoter;
use App\Security\Voter\MatchGameVoter;
use App\Security\Voter\PersonVoter;
use App\Security\Voter\TeamVoter;
use App\Security\Voter\UserRoleVoter;
use App\Security\Voter\UserVoter;
use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
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

        if ($this->security->isGranted(FrontVoter::FRONT)) {
            $menu->addChild(
                'panel',
                [
                    'route' => 'front',
                    'label' => 'Panel',
                ]
            );
        }
        if ($this->security->isGranted(UserVoter::LIST)) {
            $menu->addChild(
                'users',
                [
                    'route' => 'users_front',
                    'label' => 'Użytkownicy',
                ]
            );
        }
        if ($this->security->isGranted(UserRoleVoter::LIST)) {
            $menu->addChild(
                'userRoles',
                [
                    'route' => 'user_roles_front',
                    'label' => 'Role użytkowników',
                ]
            );
        }
        if ($this->security->isGranted(PersonVoter::LIST)) {
            $menu->addChild(
                'people',
                [
                    'route' => 'people_front',
                    'label' => 'Osoby',
                ]
            );
        }
        if ($this->security->isGranted(GameTypeVoter::LIST)) {
            $menu->addChild(
                'gameTypes',
                [
                    'route' => 'game_types_front',
                    'label' => 'Typy rozgrywek',
                ]
            );
        }
        if ($this->security->isGranted(ClubVoter::LIST)) {
            $menu->addChild(
                'clubs',
                [
                    'route' => 'clubs_front',
                    'label' => 'Kluby',
                ]
            );
        }
        if ($this->security->isGranted(TeamVoter::LIST)) {
            $menu->addChild(
                'teams',
                [
                    'route' => 'teams_front',
                    'label' => 'Drużyny',
                ]
            );
        }
        if ($this->security->isGranted(MatchGameVoter::LIST)) {
            $menu->addChild(
                'matchGames',
                [
                    'route' => 'match_games_front',
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
                        'route'           => 'users_front',
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
