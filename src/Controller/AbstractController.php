<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Club;
use App\Entity\MatchGame;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as BaseAbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\RouterInterface;
use WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs;

abstract class AbstractController extends BaseAbstractController
{
    protected RouterInterface $router;
    protected EventDispatcherInterface $dispatcher;
    protected Breadcrumbs $breadcrumbs;

    public function __construct(
        RouterInterface $router,
        EventDispatcherInterface $dispatcher,
        Breadcrumbs $breadcrumbs
    )
    {
        $this->router = $router;
        $this->dispatcher = $dispatcher;
        $this->breadcrumbs = $breadcrumbs;

        $this->breadcrumbs->addItem(
            'Panel',
            $this->router->generate('front')
        );
    }

    protected function getUserOrThrow(): User
    {
        $user = $this->getUser();

        if (!$user) {
            throw new \BadMethodCallException('User is not logged in.');
        }

        return $user;
    }

    protected function validateCsrf(string $id, $token): void
    {
        if (!\is_string($token) || !$this->isCsrfTokenValid($id, $token)) {
            throw new BadRequestHttpException('Invalid CSRF token.');
        }
    }

    protected function redirectToRefererOrHome(Request $request): Response
    {
        if (!$request->headers->has('Referer')) {
            return $this->redirectToRoute(
                'front',
                [],
                Response::HTTP_SEE_OTHER
            );
        }

        return $this->redirect($request->headers->get('Referer'));
    }

    protected function redirectToSingleClub(Club $club): Response
    {
        return $this->redirectToRoute(
            'club_single',
            [
                'club_id' => $club->getId()
            ],
            Response::HTTP_SEE_OTHER
        );
    }

    protected function redirectToSingleMatchGame(MatchGame $matchGame): Response
    {
        return $this->redirectToRoute(
            'match_game_single',
            [
                'match_game_id' => $matchGame->getId()
            ],
            Response::HTTP_SEE_OTHER
        );
    }

    protected function getJsonSuccessResponse(): JsonResponse
    {
        return new JsonResponse(
            [
                'success' => true,
            ]
        );
    }

    protected function getJsonFormResponse(
        FormInterface $form,
        string $template,
        ?array $variables = null
    ): JsonResponse
    {
        return new JsonResponse(
            [
                'form' => $this->renderView(
                    $template,
                    [
                        'form' => $form->createView(),
                    ] + ($variables ?? [])
                ),
            ]
        );
    }
}
