<?php
/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Matthieu Bontemps <matthieu@knplabs.com>
 * @author Thibault Duplessis <thibault.duplessis@gmail.com>
 * @author Luis Cordova <cordoval@gmail.com>
 */
class CreateAdminCommand extends ContainerAwareCommand
{

    /**
     * @see Command
     */
    protected function configure()
    {
        $this
            ->setName('app:admin:create')
            ->setDescription('Create an admin user.')
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
        $em       = $this->getContainer()->get('doctrine')->getManager();
        $username = $this->getContainer()->getParameter('admin_username');

        $admin = $em->getRepository('AppBundle:Person')->findOneBy(['username' => $username]);

        if (empty($admin)) {
            $firstname = $this->getContainer()->getParameter('admin_first_name');
            $lastname  = $this->getContainer()->getParameter('admin_last_name');
            $email     = $this->getContainer()->getParameter('admin_email');
            $password  = $this->getContainer()->getParameter('admin_password');
            $type      = 'admin';

            $manipulator = $this->getContainer()->get('app.tools.user_manipulator');
            $manipulator->setType($type);
            $manipulator->setFirstname($firstname);
            $manipulator->setLastname($lastname);
            $manipulator->create($username, $password, $email, true, true);

            $output->writeln(sprintf('Created user <comment>%s</comment>',
                    $username));
        } else {
            $output->writeln(sprintf('User <comment>%s</comment> already exists',
                    $username));
        }
    }
}
