<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;

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

    protected function configure()
    {
        $this
            ->setName('app:import')
            ->setDescription('Importation CSV');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $csv = $this->parseCSV($output);
        $output->writeln("Items successful - " . $this->csvParsingOptions['countItemSuccess']);
        $output->writeln("Items processed - " . $this->csvParsingOptions['countItemProcessed']);
    }

    /**
     * Parse a csv file
     *
     * @return array
     */
    private function parseCSV(OutputInterface $output)
    {
        $ignoreFirstLine = $this->csvParsingOptions['ignoreFirstLine'];
        $finder = new Finder();
        $finder->files()
            ->in($this->csvParsingOptions['finderIn'])
            ->name($this->csvParsingOptions['finderName']);

        foreach ($finder as $file) {
            $csv = $file;
        }

        $rows = array();

        if (($handle = fopen($csv->getRealPath(), "r")) !== FALSE) {
            $i = 0;

            while (($data = fgetcsv($handle, null, ";")) !== FALSE) {
                $i++;
                if ($ignoreFirstLine && $i == 1) {
                    continue;
                }
                $rows[] = $data;
                $this->csvParsingOptions['countItemSuccess']++;
                $this->csvParsingOptions['countItemProcessed']++;
                $this->csvParsingOptions['countItemSkipped']++;
            }

            fclose($handle);
        }

        return $rows;
    }
}