<?php

declare(strict_types=1);

namespace App\Service;

use App\Message\Contracts\FlashMessageInterface;
use Symfony\Component\HttpFoundation\Exception\SessionNotFoundException;
use Symfony\Component\HttpFoundation\RequestStack;

class FlashManager
{
    private RequestStack $requestStack;

    public function __construct(
        RequestStack $requestStack
    )
    {
        $this->requestStack = $requestStack;
    }

    protected function flash(
        FlashMessageInterface $message,
        string $type = 'success'
    ): void
    {
        try {
            /** @var Session $session */
            $session = $this->requestStack->getSession();
            $session->getFlashBag()->add(
                $type,
                $message->getMessage()
            );
        } catch (SessionNotFoundException $e) {
            // do nothing
        }
    }
}
