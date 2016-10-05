<?php

namespace AppBundle\Importer;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;
//use Symfony\Component\Finder\Finder;
use Doctrine\Common\Persistence\ObjectManager;

class Importer {
    private $csvParsingOptions = array(
        'fileFolder' => 'app/Resources/',
        'fileName' => 'stock.csv',
        'ignoreFirstLine' => true,
        'countItemSuccess' => 0,
        'countItemSkipped' => 0,
        'countItemProcessed' => 0,
        'countItemInvalid' => 0,
        'countItemPersisted' => 0,
    );

    private $csvArrayData = array();
    private $productsArr = array();
    private $container;

    /**
     * @var ObjectManager
     */
    private $entityManager;

    public function __construct(EntityManager $em, Container $container)
    {
        $this->entityManager = $em;
        $this->container = $container;
    }

    /**
     * Parses a csv file
     *
     * @return array
     */
    public function parseCsvFile()
    {
        $csvFile = $this->csvParsingOptions['fileFolder'] . $this->csvParsingOptions['fileName'];

        if (!file_exists($csvFile)) {
            throw new Exception("File open failed.");
        }

        $ignoreFirstLine = $this->csvParsingOptions['ignoreFirstLine'];

        if (($handle = fopen($csvFile, "r")) !== FALSE) {
            $i = 0;

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

       // var_dump($this->productsArr);die;
    }

    /**
     * Confirms import rules for csv row
     *
     * @param $row
     */
    private function confirmImportRules($rowData)
    {
        /*if (isset($row[3]) && isset($row[4]) && $row[3] != "" && $row[4] != "") {
            if ($row[3] > 10 && $row[4] > 5 && $row[4] < 1000) {
                if ($isDiscounted) {
                    $row[6] = new \DateTime();
                }

                $this->csvArrayData[] = $row;
                $this->incCountItemSuccess();

                return;
            }
        } else {
            $this->incCountItemInvalid();
        }*/

        $helper = $this->container->get('csv.helper');
        $product = $helper->getProductEntityFromCsvRow($rowData);

        if ($product != null) {
            $this->productsArr[] = $product;
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

    private function incCountItemInvalid()
    {
        $this->csvParsingOptions['countItemInvalid']++;
    }

    private function incCountItemPersisted()
    {
        $this->csvParsingOptions['countItemPersisted']++;
    }

    private function setCountItemPersistedAsZero()
    {
        $this->csvParsingOptions['countItemPersisted'] = 0;
    }

    public function outputImportationStatistic()
    {
        return "Items successful - " . $this->csvParsingOptions['countItemSuccess'] . PHP_EOL .
        "Items processed - " . $this->csvParsingOptions['countItemProcessed'] . PHP_EOL .
        "Items skipped - " . $this->csvParsingOptions['countItemSkipped'] . PHP_EOL .
        "Items have invalid format - " . $this->csvParsingOptions['countItemInvalid'];
    }

    /**
     * Inserts parsed data into database
     */
    public function insertProductsIntoDb()
    {
        //$this->entityManager->persist($this->productsArr);
        //if ($this->csvParsingOptions['countItemPersisted']) {


        foreach($this->productsArr as $index => $product) {
            $this->entityManager->persist($product);
            $this->incCountItemPersisted();

            $ind = $index + 1;

            if ($ind % 5 == 0) {
                $this->entityManager->flush();
            }
        }

        if ($this->entityManager->getUnitOfWork()->getScheduledEntityInsertions()) {

        };

         /*else {
            $this->entityManager->flush();
            $this->setCountItemPersistedAsZero();
        }*/
    }
}