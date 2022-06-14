<?php

declare(strict_types=1);

namespace App\Controller\User;

use App\Dto\UserDto;
use App\Entity\User;
use App\Form\UserBasicType;
use App\Form\UserPasswordType;
use App\Security\Voter\UserVoter;
use App\Service\UserManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserEditController extends UserAbstractController
{
    #[ParamConverter('user', options: ['mapping' => ['user_id' => 'id']])]
    public function __invoke(User $user, Request $request): Response
    {
        $this->denyAccessUnlessGranted(UserVoter::EDIT, $user);

        $this->breadcrumbs->addItem(
            'Edytuj użytkownika',
            $this->router->generate(
                'user_edit',
                [
                    'user_id' => $user->getId(),
                ]
            )
        );

        $dto = $this->manager->createDto($user);

        $basicForm = $this->handleForm(
            $this->createForm(UserBasicType::class, $dto),
            $user,
            $dto,
            $this->manager,
            $request
        );
        if (!$basicForm instanceof FormInterface) {
            return $basicForm;
        }

        $passwordForm = $this->handleForm(
            $this->createForm(UserPasswordType::class, $dto),
            $user,
            $dto,
            $this->manager,
            $request
        );
        if (!$passwordForm instanceof FormInterface) {
            return $passwordForm;
        }

        return $this->render(
            'user/edit.html.twig',
            [
                'form_basic'    => $basicForm->createView(),
                'form_password' => $passwordForm->createView(),
                'user'          => $user,
            ],
            new Response(
                null,
                $basicForm->isSubmitted() && !$basicForm->isValid()
                || $passwordForm->isSubmitted() && !$passwordForm->isValid()
                    ? Response::HTTP_UNPROCESSABLE_ENTITY : Response::HTTP_OK
            )
        );
    }

    private function handleForm(
        FormInterface $form,
        User $user,
        UserDto $dto,
        UserManager $manager,
        Request $request
    ): FormInterface|Response
    {
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->edit($user, $dto);

            /** @var ClickableInterface $continueButton */
            $continueButton = $form->get('saveAndContinue');
            if ($continueButton->isClicked()) {
                return $this->redirectToEditUser($user);
            }

            return $this->redirectToUsersList();
        }

        return $form;
    }
}
