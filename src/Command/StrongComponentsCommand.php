<?php

namespace NodesAndEdges\Command;

use Graphp\Graph\EdgeDirected as DirectedEdge;
use Graphp\Graph\Graph as Grafh;
use Graphp\GraphViz\GraphViz;
use NodesAndEdges\Digraph;
use NodesAndEdges\DSCC;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class StrongComponentsCommand
 * @package NodesAndEdges\Command
 */
class StrongComponentsCommand extends Command
{
    /**
     * The name of the command (the part after "bin/console")
     * @var string
     */
    protected static $defaultName = 'nae:scc';

    /**
     *
     */
    protected function configure()
    {
        $this
            ->setDescription('Print the strongly connected components of a graph')
            ->setHelp('This command allows you to load a file that contains graph information and prints the strongly connected components of the graph')
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
        $dscc = new DSCC($graph);
        // get the count
        $n = $dscc->count();
        // display it
        $output->writeln(sprintf('%d components', $n));
        $graphviz = new GraphViz();
        // init
        $components = [];

        // iterate over the amount of components
        for ($i = 0; $i < $n; $i++) {
            // placeholder for that comp
            $components[$i] = [];
        }
        // iterate over the set of vertices
        $vertices = $graph->getVertices();
        // iterate over the set
        for ($vertex = 0; $vertex < $vertices; $vertex++) {
            // find the component this vertex belongs to
            $id = $dscc->id($vertex);
            $componentsForId = &$components[$id];
            // add this vertex to that set
            // enqueue - place at the end
            array_push($componentsForId, $vertex);
        }
        // init
        $groupLabels = [];
        for ($i = 0; $i < $n; $i++) {
            // create a group label
            $groupLabels[$i] = implode(' ', $components[$i]);
        }
        // start a new graph
        $grafh = new Grafh();
        $vertexMap = [];
        // iterate over the components
        foreach ($components as $id => $vertices) {
            $groupLabel = $groupLabels[$id];
            $adjacentComponents = [];
            // collect the set of vertices that these vertices connect to
            foreach ($vertices as $vertex) {
                $neighbors = $graph->adjacent($vertex);
                // iterate over the neighbors
                foreach ($neighbors as $w) {
                    $adjacentComponent = $dscc->id($w);
                    if ($adjacentComponent == $id) {
                        // ignore self loops
                        continue;
                    }
                    // find the corresponding component
                    if (!in_array($adjacentComponent, $adjacentComponents)) {
                        $adjacentComponents[] = $adjacentComponent;
                        // get v
                        if (!empty($vertexMap[$groupLabel])) {
                            $vertexV = $vertexMap[$groupLabel];
                        } else {
                            $vertexV = $grafh->createVertex(['id' => $groupLabel]);
                            // get v
//                            $vertexV->setAttribute('graphviz.color', 'red');
                            $vertexMap[$groupLabel] = $vertexV;
                        }

                        // get w
                        $adjacentGroupLabel = $groupLabels[$adjacentComponent];
                        if (!empty($vertexMap[$adjacentGroupLabel])) {
                            $vertexW = $vertexMap[$adjacentGroupLabel];
                        } else {
                            $vertexW = $grafh->createVertex(['id' => $adjacentGroupLabel]);
                            $vertexMap[$adjacentGroupLabel] = $vertexW;
//                            $vertexW->setAttribute('graphviz.color', 'red');
                        }
                        if (!$vertexV->hasEdgeTo($vertexW)) {
                            $edge = new DirectedEdge($vertexV, $vertexW);
//                            $edge->setAttribute('graphviz.color', 'green');
                        }

                    }
                }
            }
            $output->writeln($groupLabel);
        }
        $output->writeln('Components Graph: ' . $graphviz->createImageFile($grafh));
        $output->writeln('');

        $grafh = new Grafh();
        $vertexMap = [];
        $vertices = $graph->getVertices();
        for ($vertex = 0; $vertex < $vertices; $vertex++) {
            $group = $dscc->id($vertex);
            $VGroupLabel = $groupLabels[$group];
            if (!empty($vertexMap[$vertex])) {
                $output->writeln('1fetching: ' . $vertex);
                $vertexV = $vertexMap[$vertex];
            } else {
                $vertexV = $grafh->createVertex(['id' => $vertex, 'group' => $VGroupLabel]);
                $vertexMap[$vertex] = $vertexV;
//                $vertexV->setAttribute('graphviz.color', 'red');
            }
            // get v
            // get the neighbors
            $neighbors = $graph->adjacent($vertex);
            // iterate over the set
            foreach ($neighbors as $w) {
                $group = $dscc->id($w);
                $WGroupLabel = $groupLabels[$group];
                // get w
                if (!empty($vertexMap[$w])) {
                    $vertexW = $vertexMap[$w];
                } else {
                    $vertexW = $grafh->createVertex(['id' => $w, 'group' => $WGroupLabel]);
                    $vertexMap[$WGroupLabel] = $vertexW;
//                    $vertexW->setAttribute('graphviz.color', 'red');
                }
                //
                if (!$vertexV->hasEdgeTo($vertexW)) {
                    $edge = new DirectedEdge($vertexV, $vertexW);
//                    $edge->setAttribute('graphviz.color', 'green');
                }
            }
        }
        $output->writeln('Full Graph: ' . $graphviz->createImageFile($grafh));
        //  return success signal
        return 0;
    }
}