<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Club\Frontend\Controller;

use Sudoku648\Meczyki\Club\Domain\Service\ClubManagerInterface;
use Sudoku648\Meczyki\Club\Frontend\Form\ClubType;
use Sudoku648\Meczyki\Security\Infrastructure\Voter\ClubVoter;
use Sudoku648\Meczyki\Shared\Frontend\Controller\AbstractController;
use Sudoku648\Meczyki\Shared\Frontend\Controller\Enums\FlashType;
use Sudoku648\Meczyki\Shared\Frontend\Controller\Traits\RedirectTrait;
use Sudoku648\Meczyki\Shared\Frontend\Service\BreadcrumbBuilder;
use Symfony\Component\Form\ClickableInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;

final class ClubCreateController extends AbstractController
{
    use RedirectTrait;

    public function __construct(
        private readonly TranslatorInterface $translator,
        private readonly BreadcrumbBuilder $breadcrumbBuilder,
        private readonly ClubManagerInterface $manager,
    ) {
    }

    public function __invoke(Request $request): Response
    {
        $this->denyAccessUnlessGranted(ClubVoter::CREATE);

        $this->breadcrumbBuilder
            ->add('dashboard')
            ->add('clubs_list')
            ->add('club_create');

        $form = $this->createForm(ClubType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $dto = $form->getData();

            $this->manager->create($dto);

            $this->makeFlash(FlashType::SUCCESS, $this->translator->trans(
                id: 'Club has been added.',
                domain: 'Club',
            ));

            /** @var ClickableInterface $continueButton */
            $continueButton = $form->get('saveAndContinue');
            if ($continueButton->isClicked()) {
                return $this->redirectToRefererOrHome($request);
            }

            return $this->redirectToClubsList();
        }

        return $this->render(
            'club/new.html.twig',
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
