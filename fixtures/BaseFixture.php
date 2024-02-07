<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\DataFixtures;

use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

abstract class BaseFixture extends Fixture implements FixtureGroupInterface
{
    protected readonly Generator $faker;
    protected readonly ObjectManager $manager;

    public static function getGroups(): array
    {
        return ['dev'];
    }

    public function load(ObjectManager $manager): void
    {
        $this->faker   = Factory::create('pl_PL');
        $this->manager = $manager;

        $this->loadData($manager);
    }

    abstract protected function loadData(ObjectManager $manager);

    protected function getRandomDateTime(?DateTimeImmutable $from = null): DateTimeImmutable
    {
        return new DateTimeImmutable(
            $this->faker->dateTimeBetween(
                $from ? $from->format('Y-m-d H:i:s') : '-1 year',
                'now'
            )
            ->format('Y-m-d H:i:s')
        );
    }

    protected function getRandomDate(?DateTimeImmutable $from = null): DateTimeImmutable
    {
        return new DateTimeImmutable(
            $this->faker->dateTimeBetween(
                $from ? $from->format('Y-m-d') : '-1 year',
                'now'
            )
            ->format('Y-m-d')
        );
    }

    protected function getRandomTime(?DateTimeImmutable $from = null): DateTimeImmutable
    {
        return new DateTimeImmutable(
            $this->faker->dateTimeBetween(
                $from ? $from->format('H:i') : '-1 year',
                'now'
            )
            ->format('H:i')
        );
    }
}
