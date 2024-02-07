<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\MatchGameBill\Frontend\Controller;

use Sudoku648\Meczyki\MatchGameBill\Domain\Entity\MatchGameBill;
use Sudoku648\Meczyki\MatchGameBill\Domain\Service\MatchGameBillManagerInterface;
use Sudoku648\Meczyki\Shared\Frontend\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs;

abstract class MatchGameBillAbstractController extends AbstractController
{
    public function __construct(
        protected RouterInterface $router,
        protected EventDispatcherInterface $dispatcher,
        protected Breadcrumbs $breadcrumbs,
        protected MatchGameBillManagerInterface $manager,
    ) {
    }

    protected function redirectToEditBill(MatchGameBill $matchGameBill): Response
    {
        return $this->redirectToRoute(
            'match_game_bill_edit',
            [
                'match_game_id'      => $matchGameBill->getMatchGame()->getId(),
                'match_game_bill_id' => $matchGameBill->getId(),
            ]
        );
    }
}
