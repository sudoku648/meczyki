<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\User\Frontend\Controller;

use Sudoku648\Meczyki\Security\Infrastructure\Voter\UserVoter;
use Sudoku648\Meczyki\User\Domain\Entity\User;
use Sudoku648\Meczyki\User\Domain\Event\UserHasBeenSeenEvent;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\HttpFoundation\Response;

class UserSingleController extends UserAbstractController
{
    public function __invoke(
        #[MapEntity(mapping: ['user_id' => 'id'])] User $user,
    ): Response {
        $this->denyAccessUnlessGranted(UserVoter::SHOW, $user);

        $this->breadcrumbs->addItem(
            $user->getUsername(),
            $this->router->generate(
                'user_single',
                [
                    'user_id' => $user->getId(),
                ]
            ),
            [],
            false
        );

        $this->dispatcher->dispatch((new UserHasBeenSeenEvent($user)));

        return $this->render(
            'user/single.html.twig',
            [
                'user' => $user,
            ]
        );
    }
}
