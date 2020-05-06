<?php

namespace TemplesOfCode\NodesAndEdges\BFS;

use TemplesOfCode\NodesAndEdges\Graph;

/**
 * Class BreadthFirstPaths
 * @package TemplesOfCode\NodesAndEdges\BFS
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
     *
     * @var int[]
     */
    private $edgeTo;

    /**
     * distTo[v] = number of edges shortest s-v path
     *
     * @var int[]
     */
    private $distTo;

    /**
     * @var mixed
     */
    private $sourceV;

    /**
     * @var Graph
     */
    private $graph;

    /**
     * @param Graph $graph
     * @param mixed $sourceV
     */
    public function __construct(Graph $graph, $sourceV)
    {
        // set
        $vertices = $graph->getVertices();
        // init
        $this->distTo = array_fill(0, $vertices, static::INFINITY);
        // check for data type
        if (is_array($sourceV)) {
            // handle multiple source vertices
            $sourceVertices = $sourceV;
            // iterate over the set of vertices
            foreach ($sourceVertices as $vertex) {
                // validate this vertex in the context of the given graph
                Graph::validateVertex($vertex, $vertices);
            }
        } else {
            // handle single vertex
            $sourceVertex = $sourceV;
            // validate this vertex in the context of the given graph
            Graph::validateVertex($sourceVertex, $vertices);
            // the distance to our source vertex is always zero
            $this->distTo[$sourceVertex] = 0;
        }
        // set
        $this->sourceV = $sourceV;
        // init
        $this->marked = array_fill(0, $vertices, false);
        // init
        $this->edgeTo = [];
        // set
        $this->graph = $graph;
        // invoke bfs
        $this->bfs();
        // check for data type
        if (!is_array($sourceV)) {
            // assert correct build
            assert($this->check($this->graph, $sourceV));
        }
    }

    /**
     *
     */
    private function bfs()
    {
        // init
        $queue = [];
        // check for data type
        if (is_array($this->sourceV)) {
            // iterate over the set of source vertices
            foreach ($this->sourceV as $vertex) {
                // consider this vertex visited
                $this->marked[$vertex] = true;
                // set the distance
                $this->distTo[$vertex] = 0;
                // add to the queue
                $queue[] = $vertex;
            }
        } else {
            // init
            $this->marked[$this->sourceV] = true;
            // add beginning of queue
            $queue = [$this->sourceV];
        }
        // begin loop over the queue
        while (!empty($queue)) {
            // get the next vertex
            $vertex = array_shift($queue);
            // get the neighbors
            $neighbors = $this->graph->adjacent($vertex);
            // iterate over the adjacent vertices
            foreach ($neighbors as $w) {
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

    /**
     * @param int $vertex
     * @return array|null
     */
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

    /**
     * @param Graph $graph
     * @param int $sourceVertex
     * @return bool
     */
    private function check(Graph $graph, int $sourceVertex)
    {
        // check that the distance of s = 0
        if ($this->distTo[$sourceVertex] != 0) {
            // check failed
            return false;
        }
        // init
        $vertices = $graph->getVertices();
        // check that for each edge v-w dist[w] <= dist[v] + 1
        // provided v is reachable from s
        for ($vertex = 0; $vertex < $vertices; $vertex++) {
            // get neighbors
            $neighbors = $graph->adjacent($vertex);
            // iterate over the neighbors
            foreach ($neighbors as $w) {
                // check paths
                if ($this->hasPathTo($vertex) != $this->hasPathTo($w)) {
                    // check failed
                    return false;
                }
                // check distances
                if ($this->hasPathTo($vertex) && ($this->distTo[$w] > $this->distTo[$vertex] + 1)) {
                    // check failed
                    return false;
                }
            }
        }
        // check that v = edgeTo[w] satisfies distTo[w] = distTo[v] + 1
        // provided v is reachable from s
        for ($w = 0; $w < $vertices; $w++) {
            if (!$this->hasPathTo($w) || $w == $sourceVertex) {
                // good to go
                continue;
            }
            // set
            $v = $this->edgeTo[$w];
            // check distances
            if ($this->distTo[$w] != $this->distTo[$v] + 1) {
                // check failed
                return false;
            }
        }
        // check passed
        return true;
    }
}
