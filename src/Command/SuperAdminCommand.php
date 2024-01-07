<?php

declare(strict_types=1);

namespace App\Command;

use App\Repository\Contracts\UserRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand('app:user:sa')]
class SuperAdminCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly UserRepositoryInterface $repository,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription(
                'This command allows you to grant super administrator privileges to the user.'
            )
            ->addArgument('username', InputArgument::REQUIRED)
            ->addOption('remove', 'r', InputOption::VALUE_NONE, 'Remove privileges')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io     = new SymfonyStyle($input, $output);
        $remove = $input->getOption('remove');
        $user   = $this->repository->findOneByUsername($input->getArgument('username'));

        if (!$user) {
            $io->error('User not found.');

            return Command::FAILURE;
        }

        $user->setOrRemoveSuperAdminRole($remove);
        $this->entityManager->flush();

        $remove ? $io->success('Super administrator privileges have been revoked.')
            : $io->success('Super administrator privileges has been granted.');

        return Command::SUCCESS;
    }
}
