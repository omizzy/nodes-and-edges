<?php

namespace TemplesOfCode\NodesAndEdges\DFS;

use TemplesOfCode\NodesAndEdges\Edge;

/**
 * Class EWDCC
 * @package TemplesOfCode\NodesAndEdges
 */
class EWDCC extends ConnectedComponent
{
    /**
     * Depth-first search for an DirectedGraph
     * @param $vertex
     */
    protected function dfs(int $vertex)
    {
        // we have visited now
        $this->marked[$vertex] = true;
        // set the component #
        $this->id[$vertex] = $this->count;
        // bump up the size of this component
        $this->size[$this->count]++;
        // get the neighbors
        $neighbors = $this->graph->adjacent($vertex);
        // iterate over the neighbors
        foreach ($neighbors as $neighbor) {
            /** @var Edge $neighbor */
            $w = $neighbor->other($vertex);
            // check if we have visited this vertex
            if (!$this->marked[$w]) {
                // we have not, lets visit with dfs
                $this->dfs($w);
            }
        }
    }
}
