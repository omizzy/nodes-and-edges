<?php

namespace TemplesOfCode\NodesAndEdges\DFS;

/**
 * Class UCC
 * @package TemplesOfCode\TemplesOfCOde\NodesAndEdges\DFS
 */
class UCC extends ConnectedComponent
{
    /**
     * Depth-first search for a Graph
     *
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
        foreach ($neighbors as $w) {
            // /check if we have visited this vertex
            if (!$this->marked[$w]) {
                // we have not, lets visit with dfs
                $this->dfs($w);
            }
        }
    }
}
