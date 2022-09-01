<?php

declare(strict_types=1);

namespace App\Controller\UserRole;

use App\Entity\UserRole;
use App\Form\UserRoleType;
use App\Security\Voter\UserRoleVoter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserRoleEditController extends UserRoleAbstractController
{
    #[ParamConverter('userRole', options: ['mapping' => ['user_role_id' => 'id']])]
    public function __invoke(UserRole $userRole, Request $request): Response
    {
        $this->denyAccessUnlessGranted(UserRoleVoter::EDIT, $userRole);

        $this->breadcrumbs->addItem(
            'Edytuj rolę',
            $this->router->generate(
                'user_role_edit',
                [
                    'user_role_id' => $userRole->getId(),
                ]
            )
        );

        $dto = $this->manager->createDto($userRole);

        $form = $this->createForm(UserRoleType::class, $dto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRole = $this->manager->edit($userRole, $dto);

            $this->addFlash('success', 'Rola użytkowników została zaktualizowana.');

            /** @var ClickableInterface $continueButton */
            $continueButton = $form->get('saveAndContinue');
            if ($continueButton->isClicked()) {
                return $this->redirectToEditUserRole($userRole);
            }

            return $this->redirectToUserRolesList();
        }

        return $this->render(
            'user_role/edit.html.twig',
            [
                'form'     => $form->createView(),
                'userRole' => $userRole,
            ],
            new Response(
                null,
                $form->isSubmitted() && !$form->isValid()
                    ? Response::HTTP_UNPROCESSABLE_ENTITY : Response::HTTP_OK
            )
        );
    }
}
