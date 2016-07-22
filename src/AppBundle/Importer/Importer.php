<?php

namespace AppBundle\Importer;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Finder\Finder;
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

    /**
     * @var ObjectManager
     */
    private $entityManager;

    private $validatorManager;

    public function __construct(EntityManager $em/*, ValidatorBuilder $vb*/)
    {
        $this->entityManager = $em;
//        $this->validatorManager = $vb;
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
    }

    /**
     * Confirms import rules for csv row
     *
     * @param $row
     */
    private function confirmImportRules($row)
    {
        $isDiscounted = false;

        if (isset($row[5])) {
            if ($row[5] == "yes") {
                $isDiscounted = true;
            }
        }

        /*$csvRowConstraint = new CsvRowConstraint();
        $res = $this->validatorManager->validateValue(
            $row,
            $csvRowConstraint
        );*/


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
    public function insertProductsIntoDb($product)
    {
        if ($this->csvParsingOptions['countItemPersisted']) {
        /*foreach($this->csvArrayData as $item) {
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
            }*/

            $this->entityManager->persist($product);
            $this->incCountItemPersisted();
        } else {
            $this->entityManager->flush();
            $this->setCountItemPersistedAsZero();
        }
    }
}