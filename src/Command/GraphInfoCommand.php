<?php

namespace NodesAndEdges\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use NodesAndEdges\UndirectedGraph;
use NodesAndEdges\GraphClient;

/**
 * Class GraphInfoCommand
 */ 
class GraphInfoCommand extends Command
{
    /**
     * The name of the command (the part after "bin/console")
     * @var string
     */
    protected static $defaultName = 'nae:graph-info';

    protected function configure()
    {
        $this
        ->setDescription('Print basic graph information')
        ->setHelp('This command allows you to load a file that contains graph information and print basic information about it')
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
        $graph = UndirectedGraph::fromFile($file);
        $output->writeln('Max Degrees: ' . GraphClient::maxDegree($graph));
        $output->writeln('Average Degrees: ' . GraphClient::avgDegree($graph));
        $output->writeln('Number of self loops: ' . GraphClient::numberOfSelfLoops($graph));
    }
}
