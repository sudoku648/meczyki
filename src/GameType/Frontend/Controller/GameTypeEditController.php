<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\GameType\Frontend\Controller;

use Sudoku648\Meczyki\GameType\Domain\Entity\GameType;
use Sudoku648\Meczyki\GameType\Domain\Service\GameTypeManagerInterface;
use Sudoku648\Meczyki\GameType\Frontend\Factory\GameTypeDtoFactory;
use Sudoku648\Meczyki\GameType\Frontend\Form\GameTypeType;
use Sudoku648\Meczyki\Security\Infrastructure\Voter\GameTypeVoter;
use Sudoku648\Meczyki\Shared\Frontend\Controller\AbstractController;
use Sudoku648\Meczyki\Shared\Frontend\Controller\Enums\FlashType;
use Sudoku648\Meczyki\Shared\Frontend\Controller\Traits\RedirectTrait;
use Sudoku648\Meczyki\Shared\Frontend\Service\BreadcrumbBuilder;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\Form\ClickableInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;

final class GameTypeEditController extends AbstractController
{
    use RedirectTrait;

    public function __construct(
        private readonly TranslatorInterface $translator,
        private readonly BreadcrumbBuilder $breadcrumbBuilder,
        private readonly GameTypeManagerInterface $manager,
    ) {
    }

    public function __invoke(
        #[MapEntity(mapping: ['game_type_id' => 'id'])] GameType $gameType,
        Request $request,
    ): Response {
        $this->denyAccessUnlessGranted(GameTypeVoter::EDIT, $gameType);

        $this->breadcrumbBuilder
            ->add('dashboard')
            ->add('game_types_list')
            ->add('game_type_edit', ['game_type_id' => $gameType->getId()]);

        $dto = GameTypeDtoFactory::fromEntity($gameType);

        $form = $this->createForm(GameTypeType::class, $dto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $gameType = $this->manager->edit($gameType, $dto);

            $this->makeFlash(FlashType::SUCCESS, $this->translator->trans(
                id: 'Game type has been updated.',
                domain: 'GameType',
            ));

            /** @var ClickableInterface $continueButton */
            $continueButton = $form->get('saveAndContinue');
            if ($continueButton->isClicked()) {
                return $this->redirectToEditGameType($gameType);
            }

            return $this->redirectToGameTypesList();
        }

        return $this->render(
            'game_type/edit.html.twig',
            [
                'form'     => $form->createView(),
                'gameType' => $gameType,
            ],
            new Response(
                null,
                $form->isSubmitted() && !$form->isValid()
                    ? Response::HTTP_UNPROCESSABLE_ENTITY : Response::HTTP_OK,
            ),
        );
    }
}
