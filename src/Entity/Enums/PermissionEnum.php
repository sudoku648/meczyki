<?php

declare(strict_types=1);

namespace App\Entity\Enums;

enum PermissionEnum: string
{
    case MANAGE_CLUBS       = 'manage_clubs';
    case MANAGE_GAME_TYPES  = 'manage_game_types';
    case MANAGE_MATCH_GAMES = 'manage_match_games';
    case MANAGE_PEOPLE      = 'manage_people';
    case MANAGE_USERS       = 'manage_users';

    public function getLabel(): string
    {
        return match ($this) {
            self::MANAGE_CLUBS       => 'Zarządzanie klubami',
            self::MANAGE_GAME_TYPES  => 'Zarządzanie typami rozgrywek',
            self::MANAGE_MATCH_GAMES => 'Zarządzanie meczami',
            self::MANAGE_PEOPLE      => 'Zarządzanie osobami',
            self::MANAGE_USERS       => 'Zarządzanie użytkownikami',
            default                  => throw new \LogicException(
                \sprintf(
                    'Permission "%s" has no label.',
                    $this->name
                )
            ),
        };
    }
}
