<?php

declare(strict_types=1);

namespace App\Controller\Person;

use App\Entity\Person;
use App\Message\Flash\Person\PersonDeletedBatchFlashMessage;
use App\Message\Flash\Person\PersonDeletedFlashMessage;
use App\Message\Flash\Person\PersonNotAllDeletedBatchFlashMessage;
use App\Repository\PersonRepository;
use App\Security\Voter\PersonVoter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PersonDeleteController extends PersonAbstractController
{
    #[ParamConverter('person', options: ['mapping' => ['person_id' => 'id']])]
    public function delete(Person $person, Request $request): Response
    {
        $this->denyAccessUnlessGranted(PersonVoter::DELETE, $person);

        $this->validateCsrf('person_delete', $request->request->get('_token'));

        $this->flash(new PersonDeletedFlashMessage($person->getId()));

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
            $this->flash(new PersonNotAllDeletedBatchFlashMessage(), 'warning');
        } else {
            $this->flash(new PersonDeletedBatchFlashMessage());
        }

        return $this->redirectToPeopleList();
    }
}
