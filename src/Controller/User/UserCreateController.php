<?php

declare(strict_types=1);

namespace App\Controller\User;

use App\Form\UserCreateType;
use App\Message\Flash\User\UserCreatedFlashMessage;
use App\Security\Voter\UserVoter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserCreateController extends UserAbstractController
{
    public function __invoke(Request $request): Response
    {
        $this->denyAccessUnlessGranted(UserVoter::CREATE);

        $this->breadcrumbs->addItem(
            'Dodaj użytkownika',
            $this->router->generate('user_create')
        );

        $form = $this->createForm(UserCreateType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $dto = $form->getData();

            $user = $this->manager->create($dto);

            $this->flash(new UserCreatedFlashMessage($user->getId()));

            /** @var ClickableInterface $continueButton */
            $continueButton = $form->get('saveAndContinue');
            if ($continueButton->isClicked()) {
                return $this->redirectToRefererOrHome($request);
            }

            return $this->redirectToUsersList();
        }

        return $this->render(
            'user/new.html.twig',
            [
                'form' => $form->createView(),
            ],
            new Response(
                null,
                $form->isSubmitted() && !$form->isValid()
                    ? Response::HTTP_UNPROCESSABLE_ENTITY : Response::HTTP_OK
            )
        );
    }
}
