<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Person\Frontend\Controller;

use Sudoku648\Meczyki\Person\Domain\Service\PersonManagerInterface;
use Sudoku648\Meczyki\Person\Frontend\Form\PersonPersonalInfoType;
use Sudoku648\Meczyki\Security\Infrastructure\Voter\PersonVoter;
use Sudoku648\Meczyki\Shared\Frontend\Controller\AbstractController;
use Sudoku648\Meczyki\Shared\Frontend\Controller\Enums\FlashType;
use Sudoku648\Meczyki\Shared\Frontend\Controller\Traits\RedirectTrait;
use Sudoku648\Meczyki\Shared\Frontend\Service\BreadcrumbBuilder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;

final class PersonPersonalInfoEditController extends AbstractController
{
    use RedirectTrait;

    public function __construct(
        private readonly TranslatorInterface $translator,
        private readonly BreadcrumbBuilder $breadcrumbBuilder,
        private readonly PersonManagerInterface $manager,
    ) {
    }

    public function __invoke(Request $request): Response
    {
        $this->denyAccessUnlessGranted(PersonVoter::EDIT_PERSONAL_INFO);

        $this->breadcrumbBuilder
            ->add('dashboard')
            ->add('person_personal_info_edit');

        $person = $this->getUserOrThrow()->getPerson();

        $dto = $this->manager->createDto($person);

        $form = $this->createForm(PersonPersonalInfoType::class, $dto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $person = $this->manager->editPersonalInfo($person, $dto);

            $this->makeFlash(FlashType::SUCCESS, $this->translator->trans(
                id: 'Personal info have been updated.',
                domain: 'Person',
            ));

            return $this->redirectToEditPersonalInfo();
        }

        return $this->render(
            'person/edit_personal_info.html.twig',
            [
                'form'   => $form->createView(),
                'person' => $person,
            ],
            new Response(
                null,
                $form->isSubmitted() && !$form->isValid()
                    ? Response::HTTP_UNPROCESSABLE_ENTITY : Response::HTTP_OK
            )
        );
    }
}
