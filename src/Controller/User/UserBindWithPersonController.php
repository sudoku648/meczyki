<?php

declare(strict_types=1);

namespace App\Controller\User;

use App\Entity\User;
use App\Form\UserBindWithPersonType;
use App\Security\Voter\UserVoter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserBindWithPersonController extends UserAbstractController
{
    #[ParamConverter('user', options: ['mapping' => ['user_id' => 'id']])]
    public function __invoke(User $user, Request $request): Response
    {
        $this->denyAccessUnlessGranted(UserVoter::BIND_WITH_PERSON, $user);

        $this->breadcrumbs
            ->addItem(
                $user->getUsername(),
                $this->router->generate(
                    'user_single',
                    [
                        'user_id' => $user->getId(),
                    ]
                ),
                [],
                false
            )
            ->addItem(
                'Połącz z osobą',
                $this->router->generate(
                    'user_bind_with_person',
                    [
                        'user_id' => $user->getId(),
                    ]
                )
            )
        ;

        $dto = $this->manager->createUserBindWithPersonDto($user);

        $form = $this->createForm(UserBindWithPersonType::class, $dto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($dto->person) {
                $this->manager->bindWithPerson($user, $dto->person);
            } else {
                $this->manager->unbindPerson($user);
            }

            /** @var ClickableInterface $continueButton */
            $continueButton = $form->get('saveAndContinue');
            if ($continueButton->isClicked()) {
                return $this->redirectToUserBindWithPerson($user);
            }

            return $this->redirectToRoute(
                'users_front',
                [],
                Response::HTTP_SEE_OTHER
            );
        }

        return $this->render(
            'user/bind_with_person.html.twig',
            [
                'form' => $form->createView(),
                'user' => $user,
            ],
            new Response(
                null,
                $form->isSubmitted() && !$form->isValid()
                    ? Response::HTTP_UNPROCESSABLE_ENTITY : Response::HTTP_OK
            )
        );
    }
}