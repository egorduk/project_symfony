<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Tests\Command;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Finder\Finder;

/**
 * Functional test that implements a "smoke test" of all the public and secure
 * URLs of the application.
 * See http://symfony.com/doc/current/best_practices/tests.html#functional-tests.
 *
 * Execute the application tests using this command (requires PHPUnit to be installed):
 *
 *     $ cd your-symfony-project/
 *     $ phpunit -c app
 *
 */
class ImportationCommandTest extends CommandTestCase
{
    private $csvParsingOptions = array(
        'finderIn' => 'app/Resources/',
        'finderName' => 'stock.csv',
        'ignoreFirstLine' => true,
        'countItemSuccess' => 0,
        'countItemSkipped' => 0,
        'countItemProcessed' => 0,
    );

    private $csvArrayData = array();

    public function testParseCSV()
    {
        $client = self::createClient();
        $output = $this->runCommand($client, "app:import test'");
        //$em = $client->getKernel()->getContainer()->get('doctrine')->getEntityManager();
       /* $user = $em->getRepository('MyProjectBundle:User')->findOneBy(array(
            'username' => 'alex'
        ));*/
        //var_dump($output);die;
        $this->assertContains('User created', $output);
        /*$this->assertEquals('Alexandre Salomé', $user->getFullname());
        $this->assertEquals('alex@example.org', $user->getEmail());
        $this->assertEquals(true, $user->getIsActive());*/

        $csvFile = null;
        $ignoreFirstLine = $this->csvParsingOptions['ignoreFirstLine'];
        $this->assertTrue($ignoreFirstLine);

        $finder = new Finder();
        $finder->files()
            ->in($this->csvParsingOptions['finderIn'])
            ->name($this->csvParsingOptions['finderName']);
        $iterator = $finder->getIterator();
        $iterator->rewind();
        $csvFile = $iterator->current();
        $this->assertNotNull($csvFile);

        if (($handle = fopen($csvFile->getRealPath(), "r")) !== FALSE) {
            $i = 0;

            $this->assertNotFalse(fgetcsv($handle));
            while (($data = fgetcsv($handle)) !== FALSE) {
                $i++;
                if ($ignoreFirstLine && $i == 1) {
                    continue;
                }
                $this->confirmImportRules($data);
                $this->incCountItemProcessed();
            }

            fclose($handle);
        }
    }

    private function confirmImportRules($row)
    {
        $isDiscounted = false;

        if (isset($row[5])) {
            if ($row[5] == "yes") {
                $isDiscounted = true;
            }
        }

        if (isset($row[3]) && isset($row[4])) {
            if ($row[3] > 10 && $row[4] > 5 && $row[4] < 1000) {
                if ($isDiscounted) {
                    $row[6] = new \DateTime();
                }
                $this->csvArrayData[] = $row;
                $this->incCountItemSuccess();
                return;
            }
        }

        $this->incCountItemSkipped();

        return;
    }

    private function incCountItemSuccess()
    {
        $this->csvParsingOptions['countItemSuccess']++;
    }

    private function incCountItemSkipped()
    {
        $this->csvParsingOptions['countItemSkipped']++;
    }

    private function incCountItemProcessed()
    {
        $this->csvParsingOptions['countItemProcessed']++;
    }

    private function insertIntoDb()
    {
        foreach($this->csvArrayData as $item) {
            $product = new Product();

            $dt = new \DateTime();
            $product->setAdded($dt);
            $product->setCost($item[4]);
            $product->setCode($item[0]);
            $product->setDescription($item[2]);
            $product->setName($item[1]);
            $product->setStock($item[3]);
            if (isset($item[6])) {
                $product->setDiscontinued($item[6]);
            }

            $this->entityManager->persist($product);
            $this->entityManager->flush();
        }
    }
}
