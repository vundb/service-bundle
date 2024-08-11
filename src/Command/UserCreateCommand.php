<?php

namespace Vundb\ServiceBundle\Command;

use Google\Cloud\Firestore\Filter;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Uid\Uuid;
use Vundb\ServiceBundle\Entity\User;
use Vundb\ServiceBundle\Repository\UserRepository;

#[AsCommand(
    name: 'user:create',
    description: 'Creates a new user.',
)]
class UserCreateCommand extends Command
{
    public function __construct(
        private UserRepository $userRepository
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('username', InputArgument::REQUIRED, 'A unique username');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $username = $input->getArgument('username');
        $token = Uuid::v4();

        $users = $this->userRepository->findBy(Filter::field('username', '=', $username));
        if (0 < count($users)) {
            $io->error(sprintf('User with username "%s" already exists.', $username));
            return Command::FAILURE;
        }

        $user = (new User())
            ->setUsername($username)
            ->addToken($token)
            ->setCreated(new \DateTime());

        $this->userRepository->persist($user);

        $io->info(['User was created:', 'Uid: ' . $user->getId(), 'Token: ' . $token]);

        $io->success('🙌');

        return Command::SUCCESS;
    }
}
