<?php

namespace AppBundle\Tests\Command;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use AppBundle\Command\ImportationCommand;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Command\Command;


class ImportationCommandTest extends WebTestCase
{
   /* public function testCommand()
    {
        $kernel = $this->createKernel();
        $kernel->boot();

        $application = new Application($kernel);
        $application->add(new ImportationCommand());

        $command = $application->find('app:import');
        $commandTester = new CommandTester($command);

        $commandTester->execute(
            array(
                'mode'  => 'test'
            )
        );
        $this->assertContains('Test mode', $commandTester->getDisplay());

        $commandTester->execute(
            array(
                'mode'  => ''
            )
        );
        //$this->assertContains('Test mode', $commandTester->getDisplay(), "Not a test mode");
    }*/

    /**
     * Method for testing generate command
     */
    public function testExecute()
    {
        //$this->createClient();
        $kernel = $this->createKernel();
        $kernel->boot();
        $application = new Application($kernel);
        $application->add(new ImportationCommand());
        $command = $application->find('app:import');
        $this->mockCommandDialogHelper($command);
        // Test command
        $commandTester = new CommandTester($command);
        $commandTester->execute(array('command' => $command->getName(), 'type' => 'service'));
        // Some asserts...
    }
    /**
     * Mocking command input (not to show command prompt)
     * @param Command $command
     */
    private function mockCommandDialogHelper(Command $command)
    {
        $dialog = $this->getMock('Symfony\Component\Console\Helper\DialogHelper', array('ask'));
        // Answers
        $dialog->expects($this->at(0))
            ->method('ask')
            ->will($this->returnValue('Val1'));
        $dialog->expects($this->at(1))
            ->method('ask')
            ->will($this->returnValue('Val2'));
        $dialog->expects($this->at(2))
            ->method('ask')
            ->will($this->returnValue('Val3'));
        $command->getHelperSet()->set($dialog, 'dialog');
    }
}
