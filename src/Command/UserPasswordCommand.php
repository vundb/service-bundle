<?php

namespace Vundb\ServiceBundle\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Vundb\ServiceBundle\Repository\UserRepository;

#[AsCommand(
    name: 'user:password',
    description: 'Sets a Password to a given User.',
)]
class UserPasswordCommand extends Command
{
    public function __construct(
        private UserRepository $userRepository,
        private UserPasswordHasherInterface $passwordHasher
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('username', InputArgument::REQUIRED, 'Username of the User where to set the Password.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        /** @var QuestionHelper $helper */
        $helper = $this->getHelper('question');

        $username = $input->getArgument('username');

        $user = $this->userRepository->findOneByUsername($username);
        if (is_null($user)) {
            $io->error(sprintf('User with username "%s" not found.', $username));
            return Command::FAILURE;
        }

        $password1 = $helper->ask($input, $output, new Question('Password: '));
        $password2 = $helper->ask($input, $output, new Question('Repeat Password: '));
        if ($password1 !== $password2) {
            $io->error('Passwords do not match.');
            return Command::FAILURE;
        }

        $hashedPassword = $this->passwordHasher->hashPassword(
            $user,
            $password1
        );

        $user->setPassword($hashedPassword);
        $this->userRepository->persist($user);
        $io->info('User Password was set.');

        $io->success('🙌');
        return Command::SUCCESS;
    }
}
