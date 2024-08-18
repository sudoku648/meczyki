<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Person\Frontend\Controller;

use Sudoku648\Meczyki\Person\Domain\Entity\Person;
use Sudoku648\Meczyki\Person\Domain\Service\PersonManagerInterface;
use Sudoku648\Meczyki\Person\Frontend\Factory\PersonDtoFactory;
use Sudoku648\Meczyki\Person\Frontend\Form\PersonType;
use Sudoku648\Meczyki\Security\Infrastructure\Voter\PersonVoter;
use Sudoku648\Meczyki\Shared\Frontend\Controller\AbstractController;
use Sudoku648\Meczyki\Shared\Frontend\Controller\Enums\FlashType;
use Sudoku648\Meczyki\Shared\Frontend\Controller\Traits\RedirectTrait;
use Sudoku648\Meczyki\Shared\Frontend\Service\BreadcrumbBuilder;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\Form\ClickableInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;

final class PersonEditController extends AbstractController
{
    use RedirectTrait;

    public function __construct(
        private readonly TranslatorInterface $translator,
        private readonly BreadcrumbBuilder $breadcrumbBuilder,
        private readonly PersonDtoFactory $factory,
        private readonly PersonManagerInterface $manager,
    ) {
    }

    public function __invoke(
        #[MapEntity(mapping: ['person_id' => 'id'])] Person $person,
        Request $request,
    ): Response {
        $this->denyAccessUnlessGranted(PersonVoter::EDIT, $person);

        $this->breadcrumbBuilder
            ->add('dashboard')
            ->add('people_list')
            ->add('person_edit', ['person_id' => $person->getId()]);

        $dto = $this->factory->fromEntity($person);

        $form = $this->createForm(PersonType::class, $dto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $person = $this->manager->edit($person, $dto);

            $this->makeFlash(FlashType::SUCCESS, $this->translator->trans(
                id: 'Person has been updated.',
                domain: 'Person',
            ));

            /** @var ClickableInterface $continueButton */
            $continueButton = $form->get('saveAndContinue');
            if ($continueButton->isClicked()) {
                return $this->redirectToEditPerson($person);
            }

            return $this->redirectToPeopleList();
        }

        return $this->render(
            'person/edit.html.twig',
            [
                'form'   => $form->createView(),
                'person' => $person,
            ],
            new Response(
                null,
                $form->isSubmitted() && !$form->isValid()
                    ? Response::HTTP_UNPROCESSABLE_ENTITY : Response::HTTP_OK,
            ),
        );
    }
}
