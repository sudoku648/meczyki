<?php

declare(strict_types=1);

namespace App\Controller\User;

use App\Entity\User;
use App\Event\User\UserHasBeenSeenEvent;
use App\Security\Voter\UserVoter;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\HttpFoundation\Response;

class UserSingleController extends UserAbstractController
{
    public function __invoke(
        #[MapEntity(mapping: ['user_id' => 'id'])] User $user
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