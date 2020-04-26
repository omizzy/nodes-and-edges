<?php

namespace NodesAndEdges;

use InvalidArgumentException;

/**
 * Class DepthFirstSearch
 * @package NodesAndEdges
 */
abstract class DepthFirstSearch
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
     * @var Graph $graph
     * @var int $sourceVertex
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
     * @param int       $vertex
     * @return bool     true if there is a path, false otherwise
     * @throws InvalidArgumentException unless 0 <= $vertex < $vertices
     */
    public function marked(int $vertex) {
        // convenience var
        $vertices = $this->graph->getVertices();
        // validate this vertex in the context of the given graph
        UndirectedGraph::validateVertex($vertex, $vertices);
        // return the flag
        return $this->marked[$vertex];
    }

    /**
     * @param int $vertex
     * @return mixed
     */
    abstract protected function dfs(int $vertex);
}
