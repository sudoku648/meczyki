<?php

declare(strict_types=1);

namespace App\EventSubscriber\Api;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\ApiDataProvider\DtoPaginator;
use App\Dto\ClubDto;
use App\Dto\GameTypeDto;
use App\Dto\MatchGameDto;
use App\Dto\TeamDto;
use App\Dto\UserDto;
use App\Factory\ClubFactory;
use App\Factory\GameTypeFactory;
use App\Factory\ImageFactory;
use App\Factory\PersonFactory;
use App\Factory\TeamFactory;
use App\Factory\UserFactory;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class ApiEventSubscriber implements EventSubscriberInterface
{
    private ClubFactory $clubFactory;
    private GameTypeFactory $gameTypeFactory;
    private ImageFactory $imageFactory;
    private PersonFactory $personFactory;
    private TeamFactory $teamFactory;
    private UserFactory $userFactory;

    public function __construct(
        ClubFactory $clubFactory,
        GameTypeFactory $gameTypeFactory,
        ImageFactory $imageFactory,
        PersonFactory $personFactory,
        TeamFactory $teamFactory,
        UserFactory $userFactory
    )
    {
        $this->clubFactory     = $clubFactory;
        $this->gameTypeFactory = $gameTypeFactory;
        $this->imageFactory    = $imageFactory;
        $this->personFactory   = $personFactory;
        $this->teamFactory     = $teamFactory;
        $this->userFactory     = $userFactory;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::VIEW => ['transform', EventPriorities::PRE_VALIDATE,],
        ];
    }

    public function transform(ViewEvent $event): void
    {
        if (!$event->getControllerResult()) {
            return;
        }

        switch ($dto = $event->getControllerResult()) {
            case $dto instanceof DtoPaginator:
                $this->collection($dto);
                break;
            case $dto instanceof ClubDto:
                $this->club($dto);
                break;
            case $dto instanceof GameTypeDto:
                $this->gameType($dto);
                break;
            case $dto instanceof MatchGameDto:
                $this->matchGame($dto);
                break;
            case $dto instanceof TeamDto:
                $this->team($dto);
                break;
            case $dto instanceof UserDto:
                $this->user($dto);
                break;
        }
    }

    private function collection(DtoPaginator $dtos)
    {
        foreach ($dtos->getIterator() as $dto) {
            switch ($dto) {
                case $dto instanceof DtoPaginator:
                    $this->collection($dto);
                    break;
                case $dto instanceof ClubDto:
                    $this->club($dto);
                    break;
                case $dto instanceof GameTypeDto:
                    $this->gameType($dto);
                    break;
                case $dto instanceof MatchGameDto:
                    $this->matchGame($dto);
                    break;
                case $dto instanceof TeamDto:
                    $this->team($dto);
                    break;
                case $dto instanceof UserDto:
                    $this->user($dto);
                    break;
            }
        }
    }

    private function club(ClubDto $dto): void
    {
        $dto->emblem = $dto->emblem ? $this->imageFactory->createDto($dto->emblem) : null;
    }

    private function gameType(GameTypeDto $dto): void
    {
        $dto->image = $dto->image ? $this->imageFactory->createDto($dto->image) : null;
    }

    private function matchGame(MatchGameDto $dto): void
    {
        $dto->user = $dto->user
            ? $this->userFactory->createDto($dto->user) : null;
        $dto->homeTeam = $dto->homeTeam
            ? $this->teamFactory->createDto($dto->homeTeam) : null;
        $dto->awayTeam = $dto->awayTeam
            ? $this->teamFactory->createDto($dto->awayTeam) : null;
        $dto->gameType = $dto->gameType
            ? $this->gameTypeFactory->createDto($dto->gameType) : null;
        $dto->referee = $dto->referee
            ? $this->personFactory->createDto($dto->referee) : null;
        $dto->firstAssistantReferee = $dto->firstAssistantReferee
            ? $this->personFactory->createDto($dto->firstAssistantReferee) : null;
        $dto->secondAssistantReferee = $dto->secondAssistantReferee
            ? $this->personFactory->createDto($dto->secondAssistantReferee) : null;
        $dto->fourthOfficial = $dto->fourthOfficial
            ? $this->personFactory->createDto($dto->fourthOfficial) : null;
        $dto->refereeObserver = $dto->refereeObserver
            ? $this->personFactory->createDto($dto->refereeObserver) : null;
        $dto->delegate = $dto->delegate
            ? $this->personFactory->createDto($dto->delegate) : null;
    }

    private function team(TeamDto $dto): void
    {
        $dto->club = $dto->club ? $this->clubFactory->createDto($dto->club) : null;
    }

    private function user(UserDto $dto): void
    {
        $dto->person = $dto->person ? $this->personFactory->createDto($dto->person) : null;
    }
}
