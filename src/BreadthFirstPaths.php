<?php

namespace NodesAndEdges;

use NodesAndEdges\Graph;

/**
 * Class BreadthFirstPath 
 */
class BreadthFirstPaths
{

    /**
     * @var int
     */
    private const INFINITY = PHP_INT_MAX;

    /**
     * @var bool[]
     */
    private $marked;

    /**
     * edgeTo[v] = previous edge on shortest s-v path
     * @var int[]
     */
    private $edgeTo;


    /**
     * distTo[v] = number of edges shortest s-v path
     * @var int[]
     */
    private $distTo;

    /**
     * @var int[]
     */
    private $sourceVertices;

    /**
     * @var Graph
     */
    private $graph;

    /**
     * @param Graph $graph
     * @param array $sourceVertices
     */
    public function __construct(Graph $graph, array $sourceVertices)
    {
        // init
        $this->edgeTo = [];
        // init
        $this->marked = [];
        // init
        $this->distTo = [];
        // set
        $this->sourceVertices = $sourceVertices;
        // set
        $this->graph = $graph;
        // iterate over the set of vertices
        foreach ($sourceVertices as $vertex) {
             // validate this vertex in the context of the given graph
            Graph::validateVertex($vertex, $graph->getVertices());
            // init
            $this->distTo[$vertex] = static::INFINITY;
        }
        // invoke bfs
        $this->bfs();
    }

    private function bfs()
    {
        // init
        $queue = [];
        // iterate over the set of source vertices
        foreach ($this->sourceVertices as $vertex) {
            // consider this vertex visited
            $this->marked[$vertex] = true;
            // set the distance
            $this->distTo[$vertex] = 0;
            // add to the queue
            $queue[] = $vertex;
        }
        // begin loop over the queue
        while (!empty($queue)) {
            // get the next vertex
            $vertex = $queue->array_shift($queue);
            // iterate over the adjacent vertices
            foreach ($this->graph->adjacent($vertex) as $w) {
                // process only if this vertex has been visited
                if (!$this->marked[$w]) {
                    // $w can be reached via $vertex
                    $this->edgeTo[$w] = $vertex;
                    // add to the distance
                    $this->distTo[$w] = $this->distTo[$vertex] + 1;
                    // consider this vertex visited
                    $this->marked[$w] = true;
                    // add to the queue
                    $queue[] = $w;
                }
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
        // return the flag
        return $this->marked[$vertex];
    }

    /**
     * @param int $vertex
     * @return int
     */
    public function distTo(int $vertex)
    {
         // validate this vertex in the context of the given graph
        Graph::validateVertex($vertex, $this->graph->getVertices());
        // return the value
        return $this->distTo[$vertex];
    }

    public function pathTo(int $vertex)
    {
         // validate this vertex in the context of the given graph
        Graph::validateVertex($vertex, $this->graph->getVertices());
        // check if there is a path
        if (!$this->hasPathTo($vertex)) {
            // there is no path
            return null;
        }
        // init
        $path = [];
        // iterate over the path
        for ($x = $vertex; $this->distTo[$x] != 0; $x = $this->edgeTo[$x]) {
            // add this vertex 
            array_unshift($path, $x);
        }
        // add final vertex
        array_unshift($path, $x);
        // return the path
        return $path;
    }
}
