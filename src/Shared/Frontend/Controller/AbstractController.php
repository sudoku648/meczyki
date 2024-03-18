<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Shared\Frontend\Controller;

use BadMethodCallException;
use Sudoku648\Meczyki\Club\Domain\Entity\Club;
use Sudoku648\Meczyki\MatchGame\Domain\Entity\MatchGame;
use Sudoku648\Meczyki\Shared\Frontend\Controller\Enums\FlashType;
use Sudoku648\Meczyki\User\Domain\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as BaseAbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

use function is_string;
use function str_replace;

abstract class AbstractController extends BaseAbstractController
{
    protected function getUserOrThrow(): User
    {
        $user = $this->getUser();

        if (!$user) {
            throw new BadMethodCallException('User is not logged in.');
        }

        return $user;
    }

    protected function validateCsrf(string $id, $token): void
    {
        if (!is_string($token) || !$this->isCsrfTokenValid($id, $token)) {
            throw new BadRequestHttpException('Invalid CSRF token.');
        }
    }

    protected function redirectToRefererOrHome(Request $request): Response
    {
        if (!$request->headers->has('Referer')) {
            return $this->redirectToRoute(
                'dashboard',
                [],
                Response::HTTP_SEE_OTHER
            );
        }

        return $this->redirect($request->headers->get('Referer'));
    }

    protected function makeFlash(FlashType $type, mixed $message): void
    {
        $this->addFlash($type->value, $message);
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
    ): JsonResponse {
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

    protected function escapeDTResponse(int|string $response): string
    {
        return str_replace(
            ["\r\n", "\n", "\r", '"'],
            [' ', ' ', ' ', "'"],
            (string) $response
        );
    }
}
