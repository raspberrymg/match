<?php

//src\Truckee\MatchBundle\Tests\Command\InteractiveCommandTest.php


namespace Truckee\MatchBundle\Tests\Command;

use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Truckee\MatchBundle\Command\CreateUserCommand;
use Liip\FunctionalTestBundle\Test\WebTestCase;

/**
 * Description of InteractiveCommandTest.
 *
 * @author George
 */
class InteractiveCommandTest extends WebTestCase
{
    public function setUp()
    {
        $classes = array(
            'Truckee\MatchBundle\DataFixtures\Test\LoadFocusSkillData',
            'Truckee\MatchBundle\DataFixtures\Test\LoadMinimumData',
        );
        $this->loadFixtures($classes);
    }

    public function testAdminUser()
    {
        $kernel = $this->createKernel();
        $kernel->boot();

        $application = new Application($kernel);
        $application->add(new CreateUserCommand());

        $command = $application->find('truckee_match:user:create');
        $commandTester = new CommandTester($command);

        $helper = $command->getHelper('question');
        $helper->setInputStream($this->getInputStream("administrator\n"
                        ."First\n "
                        ."Last\n "
                        ."administrator@bogus.info\n "
                        ."123Abcd\n "
                        ."admin\n"
        ));
        $commandTester->execute(array('command' => $command->getName()));

        $this->assertRegExp('*admin*', $commandTester->getDisplay());
    }

    public function testStaffUser()
    {
        $kernel = $this->createKernel();
        $kernel->boot();

        $application = new Application($kernel);
        $application->add(new CreateUserCommand());

        $command = $application->find('truckee_match:user:create');
        $commandTester = new CommandTester($command);

        $helper = $command->getHelper('question');
        $helper->setInputStream($this->getInputStream("staff\n"
                        ."First\n "
                        ."Last\n "
                        ."staff@bogus.info\n "
                        ."123Abcd\n "
                        ."staff\n"
        ));
        $commandTester->execute(array('command' => $command->getName()));

        $this->assertRegExp('*staff*', $commandTester->getDisplay());
    }

    public function testVolunteerUser()
    {
        $kernel = $this->createKernel();
        $kernel->boot();

        $application = new Application($kernel);
        $application->add(new CreateUserCommand());

        $command = $application->find('truckee_match:user:create');
        $commandTester = new CommandTester($command);

        $helper = $command->getHelper('question');
        $helper->setInputStream($this->getInputStream("volunteer\n"
                        ."First\n "
                        ."Last\n "
                        ."volunteer@bogus.info\n "
                        ."123Abcd\n "
                        ."volunteer\n"
        ));
        $commandTester->execute(array('command' => $command->getName()));

        $this->assertRegExp('*volunteer*', $commandTester->getDisplay());
    }

    /**
     * @expectedException  RuntimeException
     */
    public function testUsernameRequired()
    {
        $kernel = $this->createKernel();
        $kernel->boot();

        $application = new Application($kernel);
        $application->add(new CreateUserCommand());

        $command = $application->find('truckee:user:create');
        $commandTester = new CommandTester($command);

        $helper = $command->getHelper('question');
        $helper->setInputStream($this->getInputStream(
//                        "volunteer\n"
                        "First\n "
                        . "Last\n "
                        . "volunteer@bogus.info\n "
                        . "123Abcd\n "
                        . "volunteer\n"
        ));
        $commandTester->execute(array('command' => $command->getName()));
    }

    /**
     * @expectedException  RuntimeException
     */
    public function testFirstNameRequired()
    {
        $kernel = $this->createKernel();
        $kernel->boot();

        $application = new Application($kernel);
        $application->add(new CreateUserCommand());

        $command = $application->find('truckee:user:create');
        $commandTester = new CommandTester($command);

        $helper = $command->getHelper('question');
        $helper->setInputStream($this->getInputStream("volunteer\n"
//                        . "First\n "
                        . "Last\n "
                        . "volunteer@bogus.info\n "
                        . "123Abcd\n "
                        . "volunteer\n"
        ));
        $commandTester->execute(array('command' => $command->getName()));
    }

    /**
     * @expectedException  RuntimeException
     */
    public function testLastNameRequired()
    {
        $kernel = $this->createKernel();
        $kernel->boot();

        $application = new Application($kernel);
        $application->add(new CreateUserCommand());

        $command = $application->find('truckee:user:create');
        $commandTester = new CommandTester($command);

        $helper = $command->getHelper('question');
        $helper->setInputStream($this->getInputStream("volunteer\n"
                        . "First\n "
//                        . "Last\n "
                        . "volunteer@bogus.info\n "
                        . "123Abcd\n "
                        . "volunteer\n"
        ));
        $commandTester->execute(array('command' => $command->getName()));
    }

    /**
     * @expectedException  RuntimeException
     */
    public function testEmailRequired()
    {
        $kernel = $this->createKernel();
        $kernel->boot();

        $application = new Application($kernel);
        $application->add(new CreateUserCommand());

        $command = $application->find('truckee:user:create');
        $commandTester = new CommandTester($command);

        $helper = $command->getHelper('question');
        $helper->setInputStream($this->getInputStream("volunteer\n"
                        . "First\n "
                        . "Last\n "
//                        . "volunteer@bogus.info\n "
                        . "123Abcd\n "
                        . "volunteer\n"
        ));
        $commandTester->execute(array('command' => $command->getName()));
    }

    /**
     * @expectedException  RuntimeException
     */
    public function testPasswordRequired()
    {
        $kernel = $this->createKernel();
        $kernel->boot();

        $application = new Application($kernel);
        $application->add(new CreateUserCommand());

        $command = $application->find('truckee:user:create');
        $commandTester = new CommandTester($command);

        $helper = $command->getHelper('question');
        $helper->setInputStream($this->getInputStream("volunteer\n"
                        . "First\n "
                        . "Last\n "
                        . "volunteer@bogus.info\n "
//                        . "123Abcd\n "
                        . "volunteer\n"
        ));
        $commandTester->execute(array('command' => $command->getName()));
    }

    /**
     * @expectedException  RuntimeException
     */
    public function testTypeRequired()
    {
        $kernel = $this->createKernel();
        $kernel->boot();

        $application = new Application($kernel);
        $application->add(new CreateUserCommand());

        $command = $application->find('truckee:user:create');
        $commandTester = new CommandTester($command);

        $helper = $command->getHelper('question');
        $helper->setInputStream($this->getInputStream("volunteer\n"
                        . "First\n "
                        . "Last\n "
                        . "volunteer@bogus.info\n "
                        . "123Abcd\n "
//                        . "volunteer\n"
        ));
        $commandTester->execute(array('command' => $command->getName()));
    }

    protected function getInputStream($input)
    {
        $stream = fopen('php://memory', 'r+', false);
        fputs($stream, $input);
        rewind($stream);

        return $stream;
    }
}
