<?php

namespace NodesAndEdges;

use NodesAndEdges\UndirectedGraph;

/**
 * Class UCC
 * @package NodesAndEdges
 */
class UCC extends ConnectedComponent
{
    /**
     * Depth-first search for a Graph
     * @param Graph $g
     * @param $vertex
     */
    protected function dfs($g,  int $vertex)
    {
        /** @var UndirectedGraph $g */
        // we have visited now
        $this->marked[$vertex] = true;
        // set the component #
        $this->id[$vertex] = $this->count;
        // bump up the size of this component
        $this->size[$this->count]++;
        // get the neighbors
        $neighbors = $g->adjacent($vertex);
        // iterate over the neighbors
        foreach ($neighbors as $w) {
            // /check if we have visited this vertex
            if (!$this->marked[$w]) {
                // we have not, lets visit with dfs
                $this->dfs($g, $w);
            }
        }
    }
}
