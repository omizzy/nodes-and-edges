<?php

namespace NodesAndEdges;

use NodesAndEdges\Graph;

/**
 * Class DepthFirstPaths
 */
class DepthFirstPath
{
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
     * @var Graph   $graph
     * @var int     $sourceVertex
     */
    public function __construct(Graph $graph, int $sourceVertex)
    {
        // validate this vertex in the context of the given graph
        Graph::validateVertex($sourceVertex, $graph->getVertices());
        // init
        $this->marked = [];
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
     * @var int     $vertex
     */
    private function dfs(int $vertex) {
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
            array_unshift($path, $x);
        }
        // pop the source into the stack
        array_unshift($path, $this->sourceVertex);
        // return the stack
        return $path;
    }
}
