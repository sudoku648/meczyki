<?php

declare(strict_types=1);

namespace App\Controller\MatchGameBill;

use App\Controller\AbstractController;
use App\Entity\MatchGameBill;
use App\Service\MatchGameBillManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use WhiteOctober\BreadcrumbsBundle\Model\Breadcrumbs;

abstract class MatchGameBillAbstractController extends AbstractController
{
    protected MatchGameBillManager $manager;
    protected RouterInterface $router;
    protected EventDispatcherInterface $dispatcher;
    protected Breadcrumbs $breadcrumbs;

    public function __construct(
        MatchGameBillManager $manager,
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
    }

    protected function redirectToEditBill(MatchGameBill $matchGameBill): Response
    {
        return $this->redirectToRoute(
            'bill_edit',
            [
                'match_game_id' => $matchGameBill->getMatchGame()->getId(),
                'bill_id'       => $matchGameBill->getId(),
            ]
        );
    }
}
