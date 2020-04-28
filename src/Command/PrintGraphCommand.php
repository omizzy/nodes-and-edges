<?php

namespace NodesAndEdges\Command;

use Graphp\GraphViz\GraphViz;
use Graphp\Graph\EdgeDirected as DirectedEdge;
use Graphp\Graph\Graph as Grafh;
use NodesAndEdges\Digraph;
use NodesAndEdges\UndirectedGraph;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

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
        )->addOption(
            'digraph',
            null,
            InputOption::VALUE_NONE,
            'Switch to digraph mode'
        );
    }

    /**
     * todo: simply all of this when polymorphism improves
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // scope in the argument
        $file = $input->getArgument('file');
        if ($input->getOption('string')) {
            // get the content
            $content = file_get_contents($file);
            // detect di-graph mode
            if ($input->getOption('digraph')) {
                // build the graph
                $graph = Digraph::fromString($content);
            } else {
                // build the graphs
                $graph = UndirectedGraph::fromString($content);
            }
        } else {
            // detect di-graph mode
            if ($input->getOption('digraph')) {
                // build the graph
                $graph = Digraph::fromFile($file);
            } else {
                // build the graph
                $graph = UndirectedGraph::fromFile($file);
            }
        }

        $output->writeln($graph);
        $grafh = new Grafh();
        $vertices = $graph->getVertices();
        for ($vertex = 0; $vertex < $vertices; $vertex++) {
            if ($grafh->hasVertex($vertex)) {
                $vertexV = $grafh->getVertex($vertex);
            } else {
                $vertexV = $grafh->createVertex($vertex);
            }
            // get v
            $vertexV->setAttribute('graphviz.color', 'red');
            // get the neighbors
            $neighbors = $graph->adjacent($vertex);
            // iterate over the set
            foreach ($neighbors as $w) {
                // get w
                if ($grafh->hasVertex($w)) {
                    $vertexW = $grafh->getVertex($w);
                } else {
                    $vertexW = $grafh->createVertex($w);
                }
                $vertexW->setAttribute('graphviz.color', 'red');
                //
                if (!$vertexV->hasEdgeTo($vertexW)) {
                    $edge = new DirectedEdge($vertexV, $vertexW);
                    $edge->setAttribute('graphviz.color', 'green');
                }
            }
        }
        $graphviz = new GraphViz();
        $output->writeln($graphviz->createImageFile($grafh));
    }
}
