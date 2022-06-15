<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends BaseFixture
{
    const USERS_COUNT = 2;

    private UserPasswordHasherInterface $hasher;

    public function __construct(
        UserPasswordHasherInterface $hasher
    )
    {
        $this->hasher = $hasher;
    }

    public function loadData(ObjectManager $manager): void
    {
        foreach ($this->provideRandomUsers(self::USERS_COUNT) as $index => $user) {
            $newUser = new User(
                $user['username'],
                $user['password']
            );

            $hashedPassword = $this->hasher->hashPassword($newUser, $user['password']);

            $newUser->setPassword($hashedPassword);

            if ($user['isSA']) {
                $newUser->setOrRemoveSuperAdminRole();
            }

            $manager->persist($newUser);

            $this->addReference('user'.'_'.$index, $newUser);

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
                'username' => \str_replace('.', '_', $this->faker->userName),
                'password' => 'secret',
                'isSA'     => false,
            ];
        }
    }
}
