<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\Security\Frontend\Controller;

use LogicException;
use Sudoku648\Meczyki\Shared\Frontend\Controller\AbstractController;

final class LogoutController extends AbstractController
{
    public function logout(): void
    {
        throw new LogicException(
            'This method can be blank - it will be intercepted by the logout key on your firewall.'
        );
    }
}
