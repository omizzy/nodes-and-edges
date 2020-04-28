<?php

namespace NodesAndEdges\Command;

use Graphp\GraphViz\GraphViz;
use Graphp\Graph\EdgeDirected as DirectedEdge;
use Graphp\Graph\Graph as Grafh;
use NodesAndEdges\DFS\DSCC;
use NodesAndEdges\Digraph;
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
        // init map
        $vertexMap = [];
        // iterate over the components
        foreach ($components as $id => $vertices) {
            // get the label for the component
            $groupLabel = $groupLabels[$id];
            // start adjacent components
            $adjacentComponents = [];
            // collect the set of vertices that these vertices connect to
            foreach ($vertices as $vertex) {
                // get the neighbors
                $neighbors = $graph->adjacent($vertex);
                // iterate over the neighbors
                foreach ($neighbors as $w) {
                    // get the neihghbor component
                    $adjacentComponent = $dscc->id($w);
                    // check for self
                    if ($adjacentComponent == $id) {
                        // ignore self loops
                        continue;
                    }
                    // find the corresponding component
                    if (!in_array($adjacentComponent, $adjacentComponents)) {
                        // add to the list
                        $adjacentComponents[] = $adjacentComponent;
                        // check for existence
                        if (!empty($vertexMap[$groupLabel])) {
                            // get it
                            $vertexV = $vertexMap[$groupLabel];
                        } else {
                            // build it
                            $vertexV = $grafh->createVertex(['id' => $groupLabel]);
                            // set it
                            $vertexMap[$groupLabel] = $vertexV;
                        }
                        // get component label
                        $adjacentGroupLabel = $groupLabels[$adjacentComponent];
                        // check if created already
                        if (!empty($vertexMap[$adjacentGroupLabel])) {
                            // get it
                            $vertexW = $vertexMap[$adjacentGroupLabel];
                        } else {
                            // build it
                            $vertexW = $grafh->createVertex(['id' => $adjacentGroupLabel]);
                            // set it
                            $vertexMap[$adjacentGroupLabel] = $vertexW;
                        }
                        /// check
                        if (!$vertexV->hasEdgeTo($vertexW)) {
                            // add it
                            $edge = new DirectedEdge($vertexV, $vertexW);
                        }

                    }
                }
            }
            // print out here
            $output->writeln($groupLabel);
        }
        // print here
        $output->writeln('Components Graph: ' . $graphviz->createImageFile($grafh));
        // visual separation
        $output->writeln('');
        // start visual map
        $grafh = new Grafh();
        // start map
        $vertexMap = [];
        // get vertices
        $vertices = $graph->getVertices();
        // iterate over the vertices
        for ($vertex = 0; $vertex < $vertices; $vertex++) {
            // get the component id
            $group = $dscc->id($vertex);
            // get the component label
            $VGroupLabel = $groupLabels[$group];
            // check if it has been built already
            if (!empty($vertexMap[$vertex])) {
                // get it
                $vertexV = $vertexMap[$vertex];
            } else {
                // build it
                $vertexV = $grafh->createVertex(['id' => $vertex, 'group' => $VGroupLabel]);
                // add it to the map
                $vertexMap[$vertex] = $vertexV;
            }
            // get the neighbors
            $neighbors = $graph->adjacent($vertex);
            // iterate over the set
            foreach ($neighbors as $w) {
                // get the component id
                $group = $dscc->id($w);
                // get the component label
                $WGroupLabel = $groupLabels[$group];
                // get w
                if (!empty($vertexMap[$w])) {
                    // get it
                    $vertexW = $vertexMap[$w];
                } else {
                    // build it
                    $vertexW = $grafh->createVertex(['id' => $w, 'group' => $WGroupLabel]);
                    // add it the map
                    $vertexMap[$WGroupLabel] = $vertexW;
                }
                // make sure it does not exist
                if (!$vertexV->hasEdgeTo($vertexW)) {
                    // add edge
                    $edge = new DirectedEdge($vertexV, $vertexW);
                }
            }
        }
        // build and print
        $output->writeln('Full Graph: ' . $graphviz->createImageFile($grafh));
        //  return success signal
        return 0;
    }
}