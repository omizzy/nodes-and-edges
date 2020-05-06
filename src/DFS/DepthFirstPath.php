<?php

namespace TemplesOfCode\NodesAndEdges\DFS;

use TemplesOfCode\NodesAndEdges\Graph;

/**
 * Class DepthFirstPath
 * @package TemplesOfCode\NodesAndEdges\DFS
 */
class DepthFirstPath
{
    /**
     * @var Graph
     */
    private $graph;

    /**
     * @var bool[]
     */
    private $marked;

    /**
     * @var int[]
     */
    private $edgeTo;

    /**
     * @var int
     */
    private $sourceVertex;

    /**
     * DepthFirstPath constructor.
     * @param Graph $graph
     * @param int $sourceVertex
     */
    public function __construct(Graph $graph, int $sourceVertex)
    {
        // validate this vertex in the context of the given graph
        Graph::validateVertex($sourceVertex, $graph->getVertices());
        // init
        $this->marked = array_fill(0, $graph->getVertices(), false);
        // set
        $this->graph = $graph;
        // set
        $this->sourceVertex = $sourceVertex;
        // execute DFS logic
        $this->dfs($sourceVertex);
    }

    /**
     * Depth first search from $vertex
     *
     * @param int $vertex
     */
    private function dfs(int $vertex)
    {
        // set this vertex as marked
        $this->marked[$vertex] = true;
        // iterate over the the vertices incident to $vertex
        foreach ($this->graph->adjacent($vertex) as $w) {
            // if we have not visited this vertex yet..
            if (!$this->marked[$w]) {
                // set
                $this->edgeTo[$w] = $vertex;
                // lets visit
                $this->dfs($w);
            }
        }
    }

    /**
     * @param int $vertex
     * @return bool
     */
    public function hasPathTo(int $vertex)
    {
        // validate this vertex in the context of the given graph
        Graph::validateVertex($vertex, $this->graph->getVertices());
        // return 
        return $this->marked[$vertex];
    }

    /**
     * @param int $vertex
     * @return array
     */
    public function pathTo(int $vertex)
    {
        // validate this vertex in the context of the given graph
        Graph::validateVertex($vertex, $this->graph->getVertices());
        // check if there is a path
        if (!$this->hasPathTo($vertex)) {
            // empty case
            return null;
        }
        // init
        $path = [];
        // pop into the stack
        for ($x = $vertex; $x != $this->sourceVertex; $x = $this->edgeTo[$x]) {
            // pop into stack
            array_unshift($path, $x);
        }
        // pop the source into the stack
        array_unshift($path, $this->sourceVertex);
        // return the stack
        return $path;
    }
}
