<?php

declare(strict_types=1);

namespace App\Controller\UserRole;

use App\Form\UserRoleType;
use App\Message\Flash\UserRole\UserRoleCreatedFlashMessage;
use App\Security\Voter\UserRoleVoter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserRoleCreateController extends UserRoleAbstractController
{
    public function __invoke(Request $request): Response
    {
        $this->denyAccessUnlessGranted(UserRoleVoter::CREATE);

        $this->breadcrumbs->addItem(
            'Dodaj rolę',
            $this->router->generate('user_role_create')
        );

        $form = $this->createForm(UserRoleType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $dto = $form->getData();

            $userRole = $this->manager->create($dto, $this->getUserOrThrow());

            $this->flash(new UserRoleCreatedFlashMessage($userRole->getId()));

            /** @var ClickableInterface $continueButton */
            $continueButton = $form->get('saveAndContinue');
            if ($continueButton->isClicked()) {
                return $this->redirectToRefererOrHome($request);
            }

            return $this->redirectToUserRolesList();
        }

        return $this->render(
            'user_role/new.html.twig',
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
