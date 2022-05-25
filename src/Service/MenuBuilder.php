<?php

declare(strict_types=1);

namespace App\Service;

use App\Security\Voter\ClubVoter;
use App\Security\Voter\FrontVoter;
use App\Security\Voter\PersonVoter;
use App\Security\Voter\UserVoter;
use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\Security\Core\Security;

final class MenuBuilder
{
    private FactoryInterface $factory;
    private Security $security;

    public function __construct(
        FactoryInterface $factory,
        Security $security
    )
    {
        $this->factory = $factory;
        $this->security = $security;
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
        if ($this->security->isGranted(PersonVoter::LIST)) {
            $menu->addChild(
                'people',
                [
                    'route' => 'people_front',
                    'label' => 'Osoby',
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
                        'route' => 'users_front',
                        'routeParameters' =>
                        [
                            '_switch_user' => '_exit',
                        ],
                        'label' => 'Wróć na swoje konto',
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
