<?php

namespace NodesAndEdges;

/**
 * Class DirectedDFS
 * @package NodesAndEdges
 */
class DirectedDepthFirstSearch extends DepthFirstSearch
{
    /**
     * @param int $vertex
     */
    protected function dfs(int $vertex)
    {
        /** @var Digraph $graph */
        $graph = $this->graph;
        // mark the visit
        $this->marked[$vertex] = true;
        // get neighbors
        $neighbors = $graph->adjacent($vertex);
        // iterate over the set
        foreach ($neighbors as $w) {
            // check for previous visit
            if (!$this->marked[$w]) {
                // has not been visited yet
                $this->dfs($w);
            }
        }
    }
}