<?php

declare(strict_types=1);

namespace Sudoku648\Meczyki\User\Cli\Command;

use Sudoku648\Meczyki\User\Domain\Persistence\UserRepositoryInterface;
use Sudoku648\Meczyki\User\Domain\Service\UserManagerInterface;
use Sudoku648\Meczyki\User\Frontend\Dto\CreateUserDto;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand('app:user:create')]
class UserCommand extends Command
{
    public function __construct(
        private readonly UserRepositoryInterface $repository,
        private readonly UserManagerInterface $manager,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('This command allows you to create or remove user account.')
            ->addArgument('username', InputArgument::REQUIRED)
            ->addArgument('password', InputArgument::REQUIRED)
            ->addOption('remove', 'r', InputOption::VALUE_NONE, 'Remove user')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io     = new SymfonyStyle($input, $output);
        $remove = $input->getOption('remove');
        $user   = $this->repository->findOneByUsername($input->getArgument('username'));

        if ($user && !$remove) {
            $io->error('User exists.');

            return Command::FAILURE;
        }

        if ($user) {
            $this->repository->remove($user);

            $io->success('The user deletion process has started.');

            return Command::SUCCESS;
        }

        $this->createUser($input, $io);

        return Command::SUCCESS;
    }

    private function createUser(InputInterface $input, SymfonyStyle $io): void
    {
        $dto = new CreateUserDto(
            $input->getArgument('username'),
            $input->getArgument('password'),
        );

        $this->manager->create($dto);

        $io->success('A user has been created.');
    }
}
