<?php

namespace NodesAndEdges\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use NodesAndEdges\UndirectedGraph;
use NodesAndEdges\DepthFirstPath;

/**
 * Class DFSPathCommand
 */ 
class DFSPathCommand extends Command
{
    /**
     * The name of the command (the part after "bin/console")
     * @var string
     */
    protected static $defaultName = 'nae:dfs-path';

    /**
     *
     */
    protected function configure()
    {
        $this
        ->setDescription('Print connected vertices and find path to given source vertex')
        ->setHelp('This command allows you to load a file that contains graph information and print the path to source vertex')
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
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // scope in the argument
        $file = $input->getArgument('file');
        // scope in the argument
        $sourceVertex = (int)$input->getArgument('sourceVertex');
        // build the graph
        $graph = UndirectedGraph::fromFile($file);
        // create an instance
        $dfs = new DepthFirstPath($graph, $sourceVertex);
        // init
        $marked = [];
        // iterate over the set of graph vertices
        for ($vertex = 0; $vertex < $graph->getVertices(); $vertex++) {
            // is this connected to the source vertex
            if ($dfs->hasPathTo($vertex)) {
                // print to screen
                $output->write(sprintf('%d to %d:  ', $sourceVertex, $vertex));

                foreach ($dfs->pathTo($vertex) as $x) {
                    if ($x == $sourceVertex) {
                        $output->write($x);
                    } else {
                        $output->write("-" . $x);
                    }
                }
                $output->writeln('');


            } else {
                $output->writeln(sprintf(
                    '%d to %d:  not connected',
                    $sourceVertex,
                    $vertex
                ));
            }
        }
        //  return success signal
        return 0;
    }
}