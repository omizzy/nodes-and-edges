<?php


namespace NodesAndEdges\Command;

use Graphp\GraphViz\GraphViz;
use Graphp\Graph\EdgeDirected as DirectedEdge;
use Graphp\Graph\Graph as Grafh;
use NodesAndEdges\DFS\TopologicalOrder;
use NodesAndEdges\Digraph;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class TopologicalOrderCommand
 * @package NodesAndEdges\Command
 */
class TopologicalOrderCommand extends Command
{
    /**
     * The name of the command (the part after "bin/console")
     *
     * @var string
     */
    protected static $defaultName = 'nae:topological-order';

    /**
     *
     */
    protected function configure()
    {
        $this
            ->setDescription('Print the ordered vertices of the given draft')
            ->setHelp('This command allows you to load a file that contains graph information and print the ordered vertices')
            ->addArgument(
                'file',
                InputArgument::REQUIRED,
                'The full path of the graph file'
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
        // build the graph
        $graph = Digraph::fromFile($file);
        // run it
        $diTopological = new TopologicalOrder($graph);
        // init
        $grafh = new Grafh();
        // start map
        $vertexMap = [];
        // check for order
        if ($diTopological->hasOrder()) {
            // get the order
            $order = $diTopological->order();
            // iterate over the set
            foreach ($order as $vertex) {
                if (!empty($vertexMap[$vertex])) {
                    // get it
                    $vertexV = $vertexMap[$vertex];
                } else {
                    // build it
                    $vertexV = $grafh->createVertex(['id' => $vertex]);
                    // add it to the map
                    $vertexMap[$vertex] = $vertexV;
                }
                // get neighbors of vertex
                $neighbors = $graph->adjacent($vertex);
                // iterate over the set
                foreach ($neighbors as $w) {
                    // check if created already
                    if (!empty($vertexMap[$w])) {
                        // get it
                        $vertexW = $vertexMap[$w];
                    } else {
                        // build it
                        $vertexW = $grafh->createVertex(['id' => $vertex]);
                        // set it
                        $vertexMap[$vertex] = $vertexW;

                    }
                    // check for presence
                    if (!$vertexV->hasEdgeTo($vertexW)) {
                        // add it
                        $edge = new DirectedEdge($vertexV, $vertexW);
                    }
                }
                // print it
                $output->writeln($vertex);
            }
            $graphviz = new GraphViz();
            // emit the file name
            $output->writeln($graphviz->createImageFile($grafh));
        } else {
            // no order
            $output->writeln('No order in G');

        }
        //  return success signal
        return 0;
    }
}