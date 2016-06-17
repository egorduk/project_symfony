<?php

namespace AppBundle\Command;

use AppBundle\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;
use Doctrine\Common\Persistence\ObjectManager;

class ImportationCommand extends ContainerAwareCommand
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

    /**
     * @var ObjectManager
     */
    private $entityManager;

    protected function configure()
    {
        $this
            ->setName('app:import')
            ->setDescription('Importation CSV')
            ->addArgument(
                'mode',
                InputArgument::OPTIONAL,
                'If test mode then arg test'
            );
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->entityManager = $this->getContainer()->get('doctrine')->getManager();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->parseCSV();

        $mode = $input->getArgument('mode');
        if ($mode != 'test') {
            $this->insertIntoDb();
        }

        $output->writeln("Items successful - " . $this->csvParsingOptions['countItemSuccess']);
        $output->writeln("Items processed - " . $this->csvParsingOptions['countItemProcessed']);
        $output->writeln("Items skipped - " . $this->csvParsingOptions['countItemSkipped']);
    }

    /**
     * Parse a csv file
     *
     * @return array
     */
    private function parseCSV()
    {
        $csvFile = null;
        $ignoreFirstLine = $this->csvParsingOptions['ignoreFirstLine'];

        $finder = new Finder();
        $finder->files()
            ->in($this->csvParsingOptions['finderIn'])
            ->name($this->csvParsingOptions['finderName']);
        $iterator = $finder->getIterator();
        $iterator->rewind();
        $csvFile = $iterator->current();

        if (($handle = fopen($csvFile->getRealPath(), "r")) !== FALSE) {
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

    /**
     * Insert parsed data into database
     */
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