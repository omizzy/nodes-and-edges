<?php

namespace NodesAndEdges;

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
    public __construct(Graph $graph, int $sourceVertex)
    {
        // validate this vertex in the context of the given graph
        Graph::validateVertex($sourceVertex, $graph->getVertices());
        // set
        $this->graph = $graph;
        // execute DFS logic
        $this->dfs($sourceVertex);
    }

    /**
     * depth first search from $vertex
     * @var int     $vertex
     */
    private function dfs(int $vertex) {
        // bump up
        $this->count++;
        // set this vertex as marked
        $this->marked[$vertex] = true;
        // iterate over the the vertices incident to $vertex
        for ($graph->adjacent($vertex) as $w) {
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
    public boolean marked(int $vertex) {
        // convinience var
        $vertices = $graph->getVertices();
        // validate this vertex in the context of the given graph
        Graph::validateVertex($vertex, $vertices);
        // return the flag
        return $this->marked[$vertex];
    }




}
