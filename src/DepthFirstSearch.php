<?php

namespace NodesAndEdges;

use InvalidArgumentException;

/**
 */
class DepthFirstSearch
{
    /**
     * @var bool[]
     */
    private $marked;

    /**
     * @var int
     */
    private $count;

    /**
     * @var Graph
     */
    private $graph;

    /**
     * @var Graph   $graph
     * @var int     $sourceVertex
     */
    public function __construct(Graph $graph, int $sourceVertex)
    {
        // validate this vertex in the context of the given graph
        Graph::validateVertex($sourceVertex, $graph->getVertices());
        // set
        $this->graph = $graph;
        // set
        $this->marked = array_fill(0, $graph->getVertices(), false);
        // execute DFS logic
        $this->dfs($sourceVertex);
    }

    /**
     * Depth first search from $vertex
     *
     * @var int     $vertex
     */
    private function dfs(int $vertex) {
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
     * Is there a path between the source vertex and vertex v
     * @param int       $vertex
     * @return bool     true if there is a path, false otherwise
     * @throws InvalidArgumentException unless 0 <= $vertex < $vertices
     */
    public function marked(int $vertex) {
        // convenience var
        $vertices = $this->graph->getVertices();
        // validate this vertex in the context of the given graph
        Graph::validateVertex($vertex, $vertices);
        // return the flag
        return $this->marked[$vertex];
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
