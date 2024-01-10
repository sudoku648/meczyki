<?php

declare(strict_types=1);

namespace App\Controller\Person;

use App\Controller\AbstractController;
use App\Entity\Person;
use App\Service\Contracts\PersonManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs;

abstract class PersonAbstractController extends AbstractController
{
    public function __construct(
        protected RouterInterface $router,
        protected EventDispatcherInterface $dispatcher,
        protected Breadcrumbs $breadcrumbs,
        protected PersonManagerInterface $manager,
    ) {
        $this->breadcrumbs->addItem(
            'Osoby',
            $this->router->generate('people_list')
        );
    }

    protected function redirectToPeopleList(): Response
    {
        return $this->redirectToRoute(
            'people_list',
            [],
            Response::HTTP_SEE_OTHER
        );
    }

    protected function redirectToDelegatesList(): Response
    {
        return $this->redirectToRoute(
            'delegates_list',
            [],
            Response::HTTP_SEE_OTHER
        );
    }

    protected function redirectToRefereesList(): Response
    {
        return $this->redirectToRoute(
            'referees_list',
            [],
            Response::HTTP_SEE_OTHER
        );
    }

    protected function redirectToRefereeObserversList(): Response
    {
        return $this->redirectToRoute(
            'referee_observers_list',
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
