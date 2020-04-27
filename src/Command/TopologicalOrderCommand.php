<?php


namespace NodesAndEdges\Command;

use Fhaculty\Graph\Edge\Directed as DirectedEdge;
use Fhaculty\Graph\Graph as Grafh;
use Graphp\GraphViz\GraphViz;
use NodesAndEdges\DiTopological;
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
        $diTopological = new DiTopological($graph);
        // init
        $grafh = new Grafh();
        // check for order
        if ($diTopological->hasOrder()) {
            // get the order
            $order = $diTopological->order();
            // iterate over the set
            foreach ($order as $vertex) {
                if ($grafh->hasVertex($vertex)) {
                    $vertexV = $grafh->getVertex($vertex);
                } else {
                    $vertexV = $grafh->createVertex($vertex);
                }
                // get v
                $vertexV->setAttribute('graphviz.color', 'red');
                // get neighbors of vertex
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
                // print it
                $output->writeln($vertex);
            }
            $graphviz = new GraphViz();
            $output->writeln($graphviz->createImageFile($grafh));
        } else {
            $output->writeln('No order in G');

        }
        //  return success signal
        return 0;
    }
}