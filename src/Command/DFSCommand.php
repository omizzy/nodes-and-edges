<?php

namespace NodesAndEdges\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use NodesAndEdges\Graph;
use NodesAndEdges\DepthFirstSearch;

/**
 * Class DFSCommand
 */ 
class DFSCommand extends Command
{
    /**
     * The name of the command (the part after "bin/console")
     * @var string
     */
    protected static $defaultName = 'nae:dfs';

    protected function configure()
    {
        $this
        ->setDescription('Print connected vertices to given source vertex')
        ->setHelp('This command allows you to load a file that contains graph information and print connected vertices to given source vertex')
        ->addArgument(
            'file',
            InputArgument::REQUIRED,
            'The full path of the graph file'
        )->addArgument(
            'sourceVertex',
            InputArgument::REQUIRED,
            'Source vertex'
        );
    }


    /**
     * @param InputInterface $input
     * @param 
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // scope in the argument
        $file = $input->getArgument('file');
        // scope in the argument
        $sourceVertex = (int)$input->getArgument('sourceVertex');
        // build the graph
        $graph = Graph::fromFile($file);
        // create an instance
        $dfs = new DepthFirstSearch($graph, $sourceVertex);
        // init
        $marked = [];
        // iterate over the set of graph vertices
        for ($vertex = 0; $vertex < $graph->getVertices(); $vertex++) {
            // is this connected to the source vertex
            if ($dfs->marked($vertex)) {
                // add
                $marked[] = $vertex;
            }
        }
        // print it
        $output->writeln(implode(' ', $marked));
        // set default
        $message = 'connected';
        // there is more than one component
        if ($dfs->count() != $graph->getVertices()) {
        // set default
            $message = 'NOT connected';
        }
        // print it
        $output->writeln($message);
    }
}
