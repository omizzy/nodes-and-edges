<?php

namespace NodesAndEdges\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use NodesAndEdges\Graph;
use Symfony\Component\Console\Input\InputOption;

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
        )->addOption(
            'string',
            null,
            InputOption::VALUE_NONE,
            'If set, the file will be read as a string and then processed'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // scope in the argument
        $file = $input->getArgument('file');
        if ($input->getOption('string')) {
            // get the content
            $content = file_get_contents($file);
            // build the graphs
            $graph = Graph::fromString($content);
        } else {
            // build the graph
            $graph = Graph::fromFile($file);
        }
        $output->writeln($graph);
    }
}
