<?php

namespace TemplesOfCode\NodesAndEdges\DFS;

use InvalidArgumentException;
use TemplesOfCode\NodesAndEdges\Graph;

/**
 * Class DepthFirstSearch
 * @package TemplesOfCode\NodesAndEdges\DFS
 */
class DepthFirstSearch
{
    /**
     * @var bool[]
     */
    protected $marked;

    /**
     * @var Graph
     */
    protected $graph;

    /**
     * @var int
     */
    private $count;

    /**
     * DepthFirstSearch constructor.
     * @param Graph $graph
     * @param int $sourceVertex
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
     * Is there a path between the source vertex and vertex v
     *
     * @param int $vertex
     * @return bool true if there is a path, false otherwise
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
     * @param int $vertex
     */
    protected function dfs(int $vertex)
    {
        // bump up
        $this->count++;
        // mark the visit
        $this->marked[$vertex] = true;
        // get neighbors
        $neighbors = $this->graph->adjacent($vertex);
        // iterate over the set
        foreach ($neighbors as $w) {
            // check for previous visit
            if (!$this->marked[$w]) {
                // has not been visited yet
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
