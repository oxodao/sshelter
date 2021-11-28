<?php
namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CreateUserCommand extends Command
{
    private const COMMAND_NAME = 'sshelter:user:create';
    private const GENERATED_PASSWORD_SIZE = 12;

    public function __construct(private EntityManagerInterface $emi)
    {
        parent::__construct(self::COMMAND_NAME);
    }

    protected function configure()
    {
        $this
            ->setName(self::COMMAND_NAME)
            ->addArgument('username', InputArgument::REQUIRED, 'The username')
            ->addArgument('password', InputArgument::OPTIONAL, 'The password')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $ss = new SymfonyStyle($input, $output);

        $username = $input->getArgument('username');
        $password = $input->getArgument('password');

        if (!$username || strlen($username) < 3) {
            $ss->error('You have not specified a username');

            return Command::FAILURE;
        }

        $shouldTellPassword = false;
        if (!$password) {
            $password = self::randomPassword();
            $shouldTellPassword = true;
        }

        $ss->info("Creating user $username ...");

        $user = (new User)->setUsername($username)->setPlainPassword($password);
        $this->emi->persist($user);
        $this->emi->flush();

        $ss->info('The user has been created' . ($shouldTellPassword ? ' with the password ' . $password : ' !'));

        return Command::SUCCESS;
    }

    const ALPHABET = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890-_\'\"@&€()[]!?,;.:/';

    // Stolen from https://stackoverflow.com/questions/6101956/generating-a-random-password-in-php
    public static function randomPassword() {
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen(self::ALPHABET) - 1; //put the length -1 in cache
        for ($i = 0; $i < self::GENERATED_PASSWORD_SIZE; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = self::ALPHABET[$n];
        }
        return implode($pass); //turn the array into a string
    }
}