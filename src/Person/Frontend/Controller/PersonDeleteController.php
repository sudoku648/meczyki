<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Person\Frontend\Controller;

use Sudoku648\Meczyki\Person\Domain\Entity\Person;
use Sudoku648\Meczyki\Person\Domain\Persistence\PersonRepositoryInterface;
use Sudoku648\Meczyki\Person\Domain\Service\PersonManagerInterface;
use Sudoku648\Meczyki\Security\Infrastructure\Voter\PersonVoter;
use Sudoku648\Meczyki\Shared\Frontend\Controller\AbstractController;
use Sudoku648\Meczyki\Shared\Frontend\Controller\Enums\FlashType;
use Sudoku648\Meczyki\Shared\Frontend\Controller\Traits\RedirectTrait;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;

class PersonDeleteController extends AbstractController
{
    use RedirectTrait;

    public function __construct(
        private readonly TranslatorInterface $translator,
        private readonly PersonManagerInterface $manager,
    ) {
    }

    public function delete(
        #[MapEntity(mapping: ['person_id' => 'id'])] Person $person,
        Request $request,
    ): Response {
        $this->denyAccessUnlessGranted(PersonVoter::DELETE, $person);

        $this->validateCsrf('person_delete', $request->request->get('_token'));

        $this->makeFlash(FlashType::SUCCESS, $this->translator->trans(
            id: 'Person has been deleted.',
            domain: 'Person',
        ));

        $this->manager->delete($person);

        return $this->redirectToPeopleList();
    }

    public function deleteBatch(PersonRepositoryInterface $repository, Request $request): Response
    {
        $this->denyAccessUnlessGranted(PersonVoter::DELETE_BATCH);

        $this->validateCsrf('person_delete_batch', $request->request->get('_token'));

        $personIds = $request->request->all('people');

        $notAllDeleted = false;
        foreach ($personIds as $personId) {
            $person = $repository->find($personId);
            if ($person) {
                if ($this->isGranted(PersonVoter::DELETE, $person)) {
                    $this->manager->delete($person);

                    continue;
                }

                $notAllDeleted = true;
            }
        }

        if ($notAllDeleted) {
            $this->makeFlash(FlashType::WARNING, $this->translator->trans(
                id: 'Not all chosen people have been deleted.',
                domain: 'Person',
            ));
        } else {
            $this->makeFlash(FlashType::SUCCESS, $this->translator->trans(
                id: 'People have been deleted.',
                domain: 'Person',
            ));
        }

        return $this->redirectToPeopleList();
    }
}
