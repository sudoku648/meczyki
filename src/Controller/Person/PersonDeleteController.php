<?php

declare(strict_types=1);

namespace App\Controller\Person;

use App\Entity\Person;
use App\Repository\PersonRepository;
use App\Security\Voter\PersonVoter;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PersonDeleteController extends PersonAbstractController
{
    public function delete(
        #[MapEntity(mapping: ['person_id' => 'id'])] Person $person,
        Request $request
    ): Response {
        $this->denyAccessUnlessGranted(PersonVoter::DELETE, $person);

        $this->validateCsrf('person_delete', $request->request->get('_token'));

        $this->addFlash('success', 'Osoba została usunięta.');

        $this->manager->delete($person);

        return $this->redirectToPeopleList();
    }

    public function deleteBatch(PersonRepository $repository, Request $request): Response
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
            $this->addFlash('warning', 'Nie wszystkie osoby zostały usunięte.');
        } else {
            $this->addFlash('success', 'Osoby zostały usunięte.');
        }

        return $this->redirectToPeopleList();
    }
}