<?php

namespace AppBundle\Importer;

use AppBundle\Entity\Product;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Doctrine\Common\Persistence\ObjectManager;

class Importer {
    private $csvParsingOptions = array(
        'fileFolder' => 'app/Resources/',
        'fileName' => 'stock.csv',
        'ignoreFirstLine' => true,
        'countItemSuccess' => 0,
        'countItemProcessed' => 0,
        'countItemInvalid' => 0,
        'countItemPersisted' => 0,
        'countItemCodeDuplicate' => 0
    );

    private $productsArr = array();

    private $productCodesArr = array();

    /**
     * @var Container
     */
    private $container;

    /**
     * @var ObjectManager
     */
    private $entityManager;

    public function __construct(EntityManager $entityManager, Container $container)
    {
        $this->entityManager = $entityManager;
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
    }

    /**
     * Confirms import rules for csv row
     *
     * @param $rowData
     */
    private function confirmImportRules($rowData)
    {
        $helper = $this->container->get('csv.helper');
        /**
         * @var Product $product
         */
        $product = $helper->getProductEntityFromCsvRow($rowData);

        if ($product != null) {
            $productCode = $product->getCode();

            if ( (isset($this->productCodesArr[$productCode]) && $this->productCodesArr[$productCode]) != $productCode ) {
                $this->productsArr[] = $product;
                $this->productCodesArr[$productCode] = $productCode;

                $this->incCountItemSuccess();
            } else {
                $this->incCountItemCodeDuplicate();
            }
        } else {
            $this->incCountItemInvalid();
        }

        return;
    }

    private function incCountItemSuccess()
    {
        $this->csvParsingOptions['countItemSuccess']++;
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

    private function incCountItemCodeDuplicate()
    {
        $this->csvParsingOptions['countItemCodeDuplicate']++;
    }

    public function outputImportationStatistic()
    {
        return "Items successful - " . $this->csvParsingOptions['countItemSuccess'] . PHP_EOL .
        "Items processed - " . $this->csvParsingOptions['countItemProcessed'] . PHP_EOL .
        "Items inserted into database - " . $this->csvParsingOptions['countItemPersisted'] . PHP_EOL .
        "Items code duplicate - " . $this->csvParsingOptions['countItemCodeDuplicate'] . PHP_EOL .
        "Items have invalid format - " . $this->csvParsingOptions['countItemInvalid'];
    }

    /**
     * Inserts all products into database
     */
    public function insertProductsIntoDb()
    {
        foreach($this->productsArr as $index => $product) {
            $this->entityManager->persist($product);
            $this->incCountItemPersisted();

            $ind = $index + 1;

            if ($ind % 10 == 0) {
                $this->entityManager->flush();
            }
        }

        $countRemainingPersistedEntities = count($this->entityManager->getUnitOfWork()->getScheduledEntityInsertions());

        if ($countRemainingPersistedEntities) {
            $this->entityManager->flush();
        };
    }
}