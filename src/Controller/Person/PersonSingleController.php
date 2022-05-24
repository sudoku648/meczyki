<?php

declare(strict_types=1);

namespace App\Controller\Person;

use App\Entity\Person;
use App\Event\Person\PersonHasBeenSeenEvent;
use App\Security\Voter\PersonVoter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;

class PersonSingleController extends PersonAbstractController
{
    #[ParamConverter('person', options: ['mapping' => ['person_id' => 'id']])]
    public function __invoke(Person $person): Response
    {
        $this->denyAccessUnlessGranted(PersonVoter::SHOW, $person);

        $this->breadcrumbs->addItem(
            $person->getFullName(),
            $this->router->generate(
                'person_single',
                [
                    'person_id' => $person->getId(),
                ]
            )
        );

        $this->dispatcher->dispatch((new PersonHasBeenSeenEvent($person)));

        return $this->render(
            'person/single.html.twig',
            [
                'person' => $person,
            ]
        );
    }
}
