<?php

namespace AppBundle\Command;

use AppBundle\Entity\Product;
use AppBundle\Validator\Constraint\CsvRowConstraint;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ImportationCommand extends ContainerAwareCommand
{
    protected $mode;

    protected function configure()
    {
        $this
            ->setName('app:import')
            ->setDescription('Importation CSV')
            ->addArgument(
                'mode',
                InputArgument::OPTIONAL,
                'If test mode then set up arg as test'
            );
    }

    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->mode = $input->getArgument('mode');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /*$validator = $this->getContainer()->get('csv.validator');
        $constraint = new CsvRowConstraint();
        $arr = [];
        $arr[0] = 0;
        $arr[1] = 1;
        $arr[2] = 2;
        $arr[3] = 11;
        $arr[4] = 6;
        var_dump($validator->isValid($arr, $constraint));die;*/

        $constraint = new CsvRowConstraint();
        $validator = $this->getContainer()->get('csv.validator');
        //var_dump($validator);die;
        $product = new Product();
        $product->setName('test');
        $product->setDescription('description');
        $product->setCode('c123');
        $product->setStock(19);
        $product->setCost(90);
        //var_dump($product);
        $errors = $this->getContainer()->get('validator')->validate($product, $constraint);
        $countErrors = count($errors);
        if (!$countErrors) {
            echo 't';
        } else {
            echo 'f';
        }
        die;

        $importer = $this->getContainer()->get('csv.importer');
        $importer->parseCsvFile();

        ($this->mode == 'test') ? $output->writeln("Test mode") : $importer->insertProductsIntoDb();

        $outputStatistic = $importer->outputImportationStatistic();
        $output->writeln($outputStatistic);
    }
}