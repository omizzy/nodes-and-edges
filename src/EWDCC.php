<?php

namespace NodesAndEdges;

/**
 * Class EWDCC
 * @package NodesAndEdges
 */
class EWDCC extends ConnectedComponent
{
    /**
     * Depth-first search for an DirectedGraph
     * @param EdgeWeightedGraph $g
     * @param $vertex
     */
    protected function dfs($g, int $vertex)
    {
        /** @var EdgeWeightedGraph $g */
        // we have visited now
        $this->marked[$vertex] = true;
        // set the component #
        $this->id[$vertex] = $this->count;
        // bump up the size of this component
        $this->size[$this->count]++;
        // get the neighbors
        $neighbors = $g->adjacent($vertex);
        // iterate over the neighbors
        foreach ($neighbors as $neighbor) {
            /** @var Edge $neighbor */
            $w = $neighbor->other($vertex);
            // check if we have visited this vertex
            if (!$this->marked[$w]) {
                // we have not, lets visit with dfs
                $this->dfs($g, $w);
            }
        }
    }
}
