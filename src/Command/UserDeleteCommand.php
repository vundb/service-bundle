<?php

namespace Vundb\ServiceBundle\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Vundb\ServiceBundle\Repository\UserRepository;

#[AsCommand(
    name: 'user:delete',
    description: 'Deletes User by given UID.',
)]
class UserDeleteCommand extends Command
{
    public function __construct(
        private UserRepository $userRepository
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('uid', InputArgument::REQUIRED, 'UID of the User.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $uid = $input->getArgument('uid');

        $this->userRepository->delete($uid);

        $io->success('🙌');

        return Command::SUCCESS;
    }
}
