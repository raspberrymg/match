<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Truckee\MatchBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

/**
 * @author Matthieu Bontemps <matthieu@knplabs.com>
 * @author Thibault Duplessis <thibault.duplessis@gmail.com>
 * @author Luis Cordova <cordoval@gmail.com>
 */
class CreateUserCommand extends ContainerAwareCommand
{
    /**
     * @see Command
     */
    protected function configure()
    {
        $this
                ->setName('truckee_match:user:create')
                ->setDescription('Create a user.')
                ->setDefinition(array(
                    new InputArgument('username', InputArgument::REQUIRED, 'A username'),
                    new InputArgument('firstname', InputArgument::REQUIRED, 'A first name'),
                    new InputArgument('lastname', InputArgument::REQUIRED, 'A last name'),
                    new InputArgument('email', InputArgument::REQUIRED, 'An email'),
                    new InputArgument('password', InputArgument::REQUIRED, 'A password'),
                    new InputArgument('type', InputArgument::REQUIRED, 'Set the user type (admin, staff, volunteer)'),
                    new InputOption('inactive', null, InputOption::VALUE_NONE, 'Set the user as inactive'),
                    new InputOption('superadmin', null, InputOption::VALUE_NONE, 'Set the user as superadmin'),
                ))
                ->setHelp(<<<EOT
The <info>app:user:create</info> command creates a user:

  <info>php app/console app:user:create</info>

This interactive shell will ask you for: username, email, first name, last name, password, and type.
                    
Available types are admin, staff, volunteer.

You can alternatively specify the username, email, first name, last name, password, and type as arguments:

  <info>php app/console app:user:create bborko borko@bogus.info Benny Borko mypassword staff</info>

You can create an inactive user (will not be able to log in):

  <info>php app/console app:user:create bborko --inactive</info>

EOT
        );
    }

    /**
     * @see Command
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $username = $input->getArgument('username');
        $firstname = $input->getArgument('firstname');
        $lastname = $input->getArgument('lastname');
        $email = $input->getArgument('email');
        $password = $input->getArgument('password');
        $type = $input->getArgument('type');
        $inactive = $input->getOption('inactive');
        $superadmin = $input->getOption('superadmin');

        $manipulator = $this->getContainer()->get('truckee_match.tools.user_manipulator');
        $manipulator->setType($type);
        $manipulator->setFirstname($firstname);
        $manipulator->setLastname($lastname);
        $manipulator->create($username, $password, $email, !$inactive, $superadmin);

        $output->writeln(sprintf('Created user <comment>%s</comment>', $username));
    }

    /**
     * @see Command
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');

        if (!$input->getArgument('username')) {
            $question = new Question('Please enter a username: ');
            $question->setValidator(function ($answer) {
                if (empty($answer)) {
                    throw new \RuntimeException(
                    'A username is required'
                    );
                }

                return $answer;
            });
            $question->setMaxAttempts(1);

            $input->setArgument('username', $helper->ask($input, $output, $question));
        }

        if (!$input->getArgument('firstname')) {
            $question = new Question('Please enter a first name: ');
            $question->setValidator(function ($answer) {
                if (empty($answer)) {
                    throw new \RuntimeException(
                    'A first name is required'
                    );
                }

                return $answer;
            });
            $question->setMaxAttempts(1);

            $input->setArgument('firstname', $helper->ask($input, $output, $question));
        }

        if (!$input->getArgument('lastname')) {
            $question = new Question('Please enter a lastname: ');
            $question->setValidator(function ($answer) {
                if (empty($answer)) {
                    throw new \RuntimeException(
                    'A last name is required'
                    );
                }

                return $answer;
            });
            $question->setMaxAttempts(1);

            $input->setArgument('lastname', $helper->ask($input, $output, $question));
        }

        if (!$input->getArgument('email')) {
            $question = new Question('Please enter an email: ');
            $question->setValidator(function ($answer) {
                if (empty($answer)) {
                    throw new \RuntimeException(
                    'An e-mail address is required'
                    );
                }

                return $answer;
            });
            $question->setMaxAttempts(1);

            $input->setArgument('email', $helper->ask($input, $output, $question));
        }

        if (!$input->getArgument('password')) {
            $question = new Question('Please enter a password: ');
            $question->setValidator(function ($answer) {
                if (empty($answer)) {
                    throw new \RuntimeException(
                    'A password is required'
                    );
                }

                return $answer;
            });
            $question->setMaxAttempts(1);

            $input->setArgument('password', $helper->ask($input, $output, $question));
        }

        if (!$input->getArgument('type')) {
            $question = new Question('Please enter a type: ');
            $question->setValidator(function ($answer) {
                if (empty($answer)) {
                    throw new \RuntimeException(
                    'A type is required'
                    );
                }

                return $answer;
            });
            $question->setMaxAttempts(1);

            $input->setArgument('type', $helper->ask($input, $output, $question));
        }
    }
}
