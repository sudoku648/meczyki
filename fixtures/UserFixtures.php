<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\DataFixtures;

use Doctrine\Persistence\ObjectManager;
use Sudoku648\Meczyki\User\Domain\Entity\User;
use Sudoku648\Meczyki\User\Domain\ValueObject\Username;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

use function str_replace;

class UserFixtures extends BaseFixture
{
    public const USERS_COUNT = 2;

    public function __construct(
        private readonly UserPasswordHasherInterface $hasher
    ) {
    }

    public function loadData(ObjectManager $manager): void
    {
        foreach ($this->provideRandomUsers(self::USERS_COUNT) as $index => $user) {
            $newUser = new User(
                Username::fromString($user['username']),
                $user['password']
            );

            $hashedPassword = $this->hasher->hashPassword($newUser, $user['password']);

            $newUser->setPassword($hashedPassword);

            if ($user['isSA']) {
                $newUser->setOrRemoveSuperAdminRole();
            }

            $manager->persist($newUser);

            $this->addReference('user' . '_' . $index, $newUser);

            $manager->flush();
        }
    }

    private function provideRandomUsers(int $count = 1): iterable
    {
        yield [
            'username' => 'admin',
            'password' => 'admin123',
            'isSA'     => true,
        ];

        yield [
            'username' => 'regularUser',
            'password' => 'secret',
            'isSA'     => false,
        ];

        for ($i = 0; $i < $count; $i++) {
            yield [
                'username' => str_replace('.', '_', $this->faker->userName()),
                'password' => 'secret',
                'isSA'     => false,
            ];
        }
    }
}
