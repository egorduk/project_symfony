<?php

namespace Tests\Command;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Console\Command\Command;


class ImportationCommandTest extends WebTestCase
{
    public $importer = null;

    public function __construct()
    {
        self::bootKernel();
        $this->importer = static::$kernel->getContainer()->get('app.importer');
    }

    public function testGetCsvFile()
    {
    }

    public function testOutputImportationStatistic()
    {
        $this->assertContains('Items successful', $this->importer->outputImportationStatistic());
        $this->assertContains('Items processed', $this->importer->outputImportationStatistic());
        $this->assertContains('Items skipped', $this->importer->outputImportationStatistic());
        $this->assertContains('Items have invalid format', $this->importer->outputImportationStatistic());
    }
}
