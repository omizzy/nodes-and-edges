<?php

namespace NodesAndEdges\Command;

use NodesAndEdges\DFS\DepthFirstSearch;
use NodesAndEdges\Digraph;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use NodesAndEdges\UndirectedGraph;

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
            'source-vertex',
            InputArgument::REQUIRED,
            'Source vertex'
        )->addOption(
            'digraph',
            '',
            InputOption::VALUE_NONE,
            'Use a directed graph instead of an undirected one.'
        );
    }


    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // scope in the argument
        $file = $input->getArgument('file');
        // scope in the argument
        $sourceVertex = (int)$input->getArgument('source-vertex');
        // get option
        $digraphOption = $input->getOption('digraph');
        if ($digraphOption) {
            // build the graph
            $graph = Digraph::fromFile($file);
            // create an instance
        } else {
            // build the graph
            $graph = UndirectedGraph::fromFile($file);
            // create an instance
        }
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
