<?php

namespace NodesAndEdges;

/**
 * Class GraphClient
 * @package NodesAndEdges
 */
class GraphClient
{
    /**
     * Maximum degree
     *
     * @param Graph $graph
     * @return int
     */
    public static function maxDegree(Graph $graph)
    {
        // init
        $max = 0;
        // get vertices
        $vertices = $graph->getVertices();
        // iterate over the set of vertices
        for ($vertex = 0; $vertex < $vertices; $vertex++) {
            // local var
            $degree = $graph->degree($vertex);
            // check if this vertex degrees are greater than the current max
            if ($degree > $max) {
                // update the max
                $max = $degree;
            }
        }
        // return the max found
        return $max;
    }

    /**
     * Average degree
     *
     * @param Graph $graph
     * @return int
     */
    public static function avgDegree(Graph $graph)
    {
        // each edge incident on two vertices
        return 2 * $graph->getEdges() / $graph->getVertices();
    }

    /**
     * number of self-loops
     *
     * @param Graph $graph
     * @return int
     */
    public static function numberOfSelfLoops(Graph $graph)
    {
        // init
        $count = 0;
        // get vertices
        $vertices = $graph->getVertices();
        // iterate over the graph vertices
        for ($vertex = 0; $vertex < $vertices; $vertex++) {
            // iterate over the adjacent vertices
            foreach ($graph->adjacent($vertex) as $w) {
                // is this vertex adjacent to itself
                if ($vertex == $w) {
                    // yes, bump up
                    $count++;
                }
            }
        }
        // self loop appears in adjacency list twice
        return $count/2;   
    }
}
