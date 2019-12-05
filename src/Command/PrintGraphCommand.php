<?php

namespace NodesAndEdges\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use NodesAndEdges\Graph;

/**
 * Class PrintGraphCommand
 */ 
class PrintGraphCommand extends Command
{
    /**
     * The name of the command (the part after "bin/console")
     * @var string
     */
    protected static $defaultName = 'nae:print-graph';

    protected function configure()
    {
        $this
        ->setDescription('Print a graph to the screen')
        ->setHelp('This command allows you to load a file that contains graph information and print it to the screen')
        ->addArgument(
            'file',
            InputArgument::REQUIRED,
            'The full path of the graph file'
        )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // scope in the argument
        $file = $input->getArgument('file');
        // build the graph
        $graph = Graph::fromFile($file);
        $output->writeln($graph);
    }
}
