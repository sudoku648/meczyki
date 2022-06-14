<?php

declare(strict_types=1);

namespace App\Controller\Person;

use App\Controller\AbstractController;
use App\Entity\Person;
use App\Service\PersonManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs;

abstract class PersonAbstractController extends AbstractController
{
    protected PersonManager $manager;
    protected RouterInterface $router;
    protected EventDispatcherInterface $dispatcher;
    protected Breadcrumbs $breadcrumbs;

    public function __construct(
        PersonManager $manager,
        RouterInterface $router,
        EventDispatcherInterface $dispatcher,
        Breadcrumbs $breadcrumbs
    )
    {
        parent::__construct(
            $router,
            $dispatcher,
            $breadcrumbs
        );

        $this->manager = $manager;

        $this->breadcrumbs->addItem(
            'Osoby',
            $this->router->generate('people_front')
        );
    }

    protected function redirectToPeopleList(): Response
    {
        return $this->redirectToRoute(
            'people_front',
            [],
            Response::HTTP_SEE_OTHER
        );
    }

    protected function redirectToDelegatesList(): Response
    {
        return $this->redirectToRoute(
            'delegates_front',
            [],
            Response::HTTP_SEE_OTHER
        );
    }

    protected function redirectToRefereesList(): Response
    {
        return $this->redirectToRoute(
            'referees_front',
            [],
            Response::HTTP_SEE_OTHER
        );
    }

    protected function redirectToRefereeObserversList(): Response
    {
        return $this->redirectToRoute(
            'referee_observers_front',
            [],
            Response::HTTP_SEE_OTHER
        );
    }

    protected function redirectToEditPerson(Person $person): Response
    {
        return $this->redirectToRoute(
            'person_edit',
            [
                'person_id' => $person->getId(),
            ]
        );
    }
}
