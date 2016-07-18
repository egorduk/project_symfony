<?php

namespace AppBundle\Command;

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
        $importer = $this->getContainer()->get('app.importer');
        $importer->parseCsvFile();

        ($this->mode == 'test') ? $output->writeln("Test mode") : $importer->insertProductsIntoDb();

        $outputStatistic = $importer->outputImportationStatistic();
        $output->writeln($outputStatistic);
    }
}