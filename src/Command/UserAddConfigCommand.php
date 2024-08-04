<?php

namespace Vundb\ServiceBundle\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Vundb\ServiceBundle\Repository\UserRepository;

#[AsCommand(
    name: 'user:config:add',
    description: 'Add a configuration key & value pair to user entity',
)]
class UserAddConfigCommand extends Command
{
    public function __construct(
        private UserRepository $userRepository
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        /** @var QuestionHelper $helper */
        $helper = $this->getHelper('question');

        $users = $this->userRepository->findByUsername(
            $helper->ask($input, $output, new Question('Username: '))
        );
        if (empty($users)) {
            $io->error('User not found');
            return Command::FAILURE;
        }

        $user = $users[0];
        $configuration = $user->getConfig();

        $name = $helper->ask($input, $output, new Question('Configuration Name: '));
        if (!array_key_exists($name, $configuration)) {
            $configuration[$name] = [];
        }

        while (true) {
            $k = $helper->ask($input, $output, new Question('Key (q for quit): '));
            if ('q' === $k) {
                break;
            }
            $v = $helper->ask($input, $output, new Question('Value (q for quit): '));
            if ('q' === $k) {
                break;
            }
            $configuration[$name][$k] = $v;
        }

        $user->setConfig($configuration);
        $this->userRepository->persist($user);

        $io->success('🙌');
        return Command::SUCCESS;
    }
}
