<?php

namespace App\Command;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ChangeUserPasswordCommand extends Command
{
    private const COMMAND_NAME = 'sshelter:user:change-password';

    public function __construct(
        private UserRepository $userRepository,
        private EntityManagerInterface $emi,
    )
    {
        parent::__construct(self::COMMAND_NAME);
    }

    protected function configure()
    {
        $this
            ->setName(self::COMMAND_NAME)
            ->addArgument('username', InputArgument::REQUIRED, 'The user')
            ->addArgument('new_password', InputArgument::OPTIONAL, 'The new password for the user, if empty, a random one will be generated')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $ss = new SymfonyStyle($input, $output);

        $username = $input->getArgument('username');
        $password = $input->getArgument('new_password');

        if (!$username || strlen($username) < 3) {
            $ss->error('You have not specified a username');

            return Command::FAILURE;
        }

        $shouldTellPassword = false;
        if (!$password) {
            $password = CreateUserCommand::randomPassword();
            $shouldTellPassword = true;
        }

        $user = $this->userRepository->findOneByUsername($username);
        if (!$user) {
            $ss->error("Could not find the user $username in database");

            return Command::FAILURE;
        }

        $user->setPlainPassword($password);
        $this->emi->persist($user);
        $this->emi->flush();

        $ss->info("The password for the user $username has been updated" . ($shouldTellPassword ? ' with the password ' . $password : ' !'));

        return Command::SUCCESS;
    }
}