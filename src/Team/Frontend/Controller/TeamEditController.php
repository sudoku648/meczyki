<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Team\Frontend\Controller;

use Sudoku648\Meczyki\Club\Domain\Entity\Club;
use Sudoku648\Meczyki\Security\Infrastructure\Voter\TeamVoter;
use Sudoku648\Meczyki\Shared\Frontend\Controller\AbstractController;
use Sudoku648\Meczyki\Shared\Frontend\Controller\Enums\FlashType;
use Sudoku648\Meczyki\Shared\Frontend\Controller\Traits\RedirectTrait;
use Sudoku648\Meczyki\Shared\Frontend\Service\BreadcrumbBuilder;
use Sudoku648\Meczyki\Team\Domain\Entity\Team;
use Sudoku648\Meczyki\Team\Domain\Service\TeamManagerInterface;
use Sudoku648\Meczyki\Team\Frontend\Factory\UpdateTeamDtoFactory;
use Sudoku648\Meczyki\Team\Frontend\Form\TeamType;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\Form\ClickableInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;

final class TeamEditController extends AbstractController
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
        #[MapEntity(mapping: ['team_id' => 'id'])] Team $team,
        Request $request,
    ): Response {
        $this->denyAccessUnlessGranted(TeamVoter::EDIT, $team);

        $this->breadcrumbBuilder
            ->add('dashboard')
            ->add('teams_list')
            ->add('team_edit', [
                'club_id' => $club->getId(),
                'team_id' => $team->getId(),
            ]);

        $dto = UpdateTeamDtoFactory::fromEntity($team);

        $form = $this->createForm(TeamType::class, $dto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $team = $this->manager->edit($team, $dto);

            $this->makeFlash(FlashType::SUCCESS, $this->translator->trans(
                id: 'Team has been updated.',
                domain: 'Team',
            ));

            /** @var ClickableInterface $continueButton */
            $continueButton = $form->get('saveAndContinue');
            if ($continueButton->isClicked()) {
                return $this->redirectToEditTeam($team);
            }

            return $this->redirectToTeamsList();
        }

        return $this->render(
            'team/edit.html.twig',
            [
                'form' => $form->createView(),
                'team' => $team,
            ],
            new Response(
                null,
                $form->isSubmitted() && !$form->isValid()
                    ? Response::HTTP_UNPROCESSABLE_ENTITY : Response::HTTP_OK,
            ),
        );
    }
}
