<?php

namespace NodesAndEdges;

use InvalidArgumentException;

/**
 * Class DepthFirstSearch
 * @package NodesAndEdges
 */
class UndirectedDepthFirstSearch extends DepthFirstSearch
{
    /**
     * @var int
     */
    private $count;

    /**
     * Depth first search from $vertex
     *
     * @var int     $vertex
     */
    protected function dfs(int $vertex) {
        // bump up
        $this->count++;
        // set this vertex as marked
        $this->marked[$vertex] = true;
        // iterate over the the vertices incident to $vertex
        foreach ($this->graph->adjacent($vertex) as $w) {
            // if we have not visited this vertex yet..
            if (!$this->marked[$w]) {
                // lets visit
                $this->dfs($w);
            }
        }
    }

    /**
     * Returns the number of vertices connected to $sourceVertex
     * 
     * @return int  the number of vertices connected to $sourceVertex
     */
    public function count()
    {
        return $this->count;
    }
}
