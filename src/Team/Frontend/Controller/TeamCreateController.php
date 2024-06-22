<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Team\Frontend\Controller;

use Sudoku648\Meczyki\Club\Domain\Entity\Club;
use Sudoku648\Meczyki\Security\Infrastructure\Voter\TeamVoter;
use Sudoku648\Meczyki\Shared\Frontend\Controller\AbstractController;
use Sudoku648\Meczyki\Shared\Frontend\Controller\Enums\FlashType;
use Sudoku648\Meczyki\Shared\Frontend\Controller\Traits\RedirectTrait;
use Sudoku648\Meczyki\Shared\Frontend\Service\BreadcrumbBuilder;
use Sudoku648\Meczyki\Team\Domain\Service\TeamManagerInterface;
use Sudoku648\Meczyki\Team\Frontend\Form\TeamType;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\Form\ClickableInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;

final class TeamCreateController extends AbstractController
{
    use RedirectTrait;

    public function __construct(
        private readonly TranslatorInterface $translator,
        private readonly BreadcrumbBuilder $breadcrumbBuilder,
        private readonly TeamManagerInterface $manager,
    ) {
    }

    public function __invoke(
        #[MapEntity(mapping: ['club_id' => 'id'])] Club $club,
        Request $request,
    ): Response {
        $this->denyAccessUnlessGranted(TeamVoter::CREATE);

        $this->breadcrumbBuilder
            ->add('dashboard')
            ->add('teams_list')
            ->add('club_single', ['club_id' => $club->getId()])
            ->add('team_create', ['club_id' => $club->getId()]);

        $form = $this->createForm(TeamType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $dto       = $form->getData();
            $dto->club = $club;

            $this->manager->create($dto);

            $this->makeFlash(FlashType::SUCCESS, $this->translator->trans(
                id: 'Team has been added.',
                domain: 'Team',
            ));

            /** @var ClickableInterface $continueButton */
            $continueButton = $form->get('saveAndContinue');
            if ($continueButton->isClicked()) {
                return $this->redirectToRefererOrHome($request);
            }

            return $this->redirectToTeamsList();
        }

        return $this->render(
            'team/new.html.twig',
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
