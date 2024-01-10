<?php

declare(strict_types=1);

namespace App\Controller\Security;

use App\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    public function __invoke(AuthenticationUtils $utils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute(
                'dashboard',
                [],
                Response::HTTP_SEE_OTHER
            );
        }

        $error        = $utils->getLastAuthenticationError();
        $lastUsername = $utils->getLastUsername();

        return $this->render(
            'security/login.html.twig',
            [
                'error'        => $error,
                'lastUsername' => $lastUsername,
            ]
        );
    }
}
