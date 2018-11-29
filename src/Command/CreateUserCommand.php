<?php
/**
 * Created by PhpStorm.
 * User: ilya
 * Date: 28/11/2018
 * Time: 16:44
 */

namespace App\Command;


use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use App\Service\RegistrationService;

class CreateUserCommand extends Command
{
    private $objRegistrationService;

    public function __construct(RegistrationService $objRegistrationService)
    {
        $this->objRegistrationService = $objRegistrationService;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('app:create-user')
            ->addArgument('username', InputArgument::REQUIRED, 'The username of the user.')
           // ->addArgument('password', InputArgument::REQUIRED, 'The password of the user.')


            // the short description shown while running "php bin/console list"
            ->setDescription('Creates a new user.')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('This command allows you to create a user...')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // outputs multiple lines to the console (adding "\n" at the end of each line)
        $output->writeln([
            'User Creator',
            '============',
            '',
        ]);


        // outputs a message followed by a "\n"
        $output->writeln('Whoa!');

        $this->objRegistrationService->registerUser($input->getArgument('username'));

        //$this->objRegistrationService->registerUser($input->getArgument('password'));



        $output->writeln('User successifly created');



        // outputs a message without adding a "\n" at the end of the line
        $output->write('You are about to ');
        $output->write('create a user.');
    }

}