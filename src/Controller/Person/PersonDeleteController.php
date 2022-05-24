<?php

declare(strict_types=1);

namespace App\Controller\Person;

use App\Entity\Person;
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

        $this->manager->delete($person);

        return $this->redirectToRefererOrHome($request);
    }
}
