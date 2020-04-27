<?php

namespace NodesAndEdges;

/**
 * Class BreadthFirstPath
 */
class BreadthFirstPath
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
     * @var int
     */
    private $sourceVertex;

    /**
     * @var Graph
     */
    private $graph;

    /**
     * @param Graph $graph
     * @param int   $sourceVertex
     */
    public function __construct(Graph $graph, int $sourceVertex)
    {
         // validate this vertex in the context of the given graph
        UndirectedGraph::validateVertex($sourceVertex, $graph->getVertices());
        // init
        $this->marked = array_fill(0, $graph->getVertices(), false);
        // init
        $this->distTo = array_fill(0, $graph->getVertices(), static::INFINITY);
        // init
        $this->edgeTo = [];
        // set
        $this->sourceVertex = $sourceVertex;
        // the distance to our source vertex is always zero
        $this->distTo[$this->sourceVertex] = 0;
        // set
        $this->graph = $graph;
        // invoke bfs
        $this->bfs();
        // validate
        assert($this->check());
    }

    /**
     *
     */
    private function bfs()
    {
        // init
        $this->marked[$this->sourceVertex] = true;
        // add beginning of queue
        $queue = [$this->sourceVertex];
        // start looping
        while (!empty($queue)) {
            // pop the next vertex
            $vertex = array_shift($queue);
            // get the adjacent vertices
            $neighbors = $this->graph->adjacent($vertex);
            // iterate over them
            foreach ($neighbors as $w) {
                // check if this vertex has been visited
                if (!$this->marked[$w]) {
                    // the edge to $w is indeed through $vertex
                    $this->edgeTo[$w] = $vertex;
                    // also compute the distance
                    $this->distTo[$w] = $this->distTo[$vertex] + 1;
                    // mark this vertex as visited
                    $this->marked[$w] = true;
                    // enqueue this vertex
                    $queue[] = $w;
                }
            }
        }
    }

    /**
     * @param int   $vertex
     * @return bool
     */
    public function hasPathTo(int $vertex)
    {
         // validate this vertex in the context of the given graph
        UndirectedGraph::validateVertex($vertex, $this->graph->getVertices());
        // return the flag
        return $this->marked[$vertex];
    }

    /**
     * @param int   $vertex
     * @return int
     */
    public function distTo(int $vertex)
    {
         // validate this vertex in the context of the given graph
        UndirectedGraph::validateVertex($vertex, $this->graph->getVertices());
        // return the value
        return $this->distTo[$vertex];
    }

    /**
     * @param int           $vertex
     * @return array|null
     */
    public function pathTo(int $vertex)
    {
         // validate this vertex in the context of the given graph
        UndirectedGraph::validateVertex($vertex, $this->graph->getVertices());
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
     * @return bool
     */
    private function check()
    {
        // check that the distance of s = 0
        if ($this->distTo[$this->sourceVertex] != 0) {
            // StdOut.println("distance of source " + s + " to itself = " + distTo[s]);
            return false;
        }

        // check that for each edge v-w dist[w] <= dist[v] + 1
        // provided v is reachable from s
        for ($vertex = 0; $vertex < $this->graph->getVertices(); $vertex++) {
            foreach ($this->graph->adjacent($vertex) as $w) {
                if ($this->hasPathTo($vertex) != $this->hasPathTo($w)) {
                    // StdOut.println("edge " + v + "-" + w);
                    // StdOut.println("hasPathTo(" + v + ") = " + hasPathTo(v));
                    // StdOut.println("hasPathTo(" + w + ") = " + hasPathTo(w));
                    return false;
                }
                if ($this->hasPathTo($vertex) && ($this->distTo[$w] > $this->distTo[$vertex] + 1)) {
                    // StdOut.println("edge " + v + "-" + w);
                    // StdOut.println("distTo[" + v + "] = " + distTo[v]);
                    // StdOut.println("distTo[" + w + "] = " + distTo[w]);
                    return false;
                }
            }
        }

        // check that v = edgeTo[w] satisfies distTo[w] = distTo[v] + 1
        // provided v is reachable from s
        for ($w = 0; $w < $this->graph->getVertices(); $w++) {
            if (!$this->hasPathTo($w) || $w == $this->sourceVertex) {
                continue;
            }
            $v = $this->edgeTo[$w];
            if ($this->distTo[$w] != $this->distTo[$v] + 1) {
                // StdOut.println("shortest path edge " + v + "-" + w);
                // StdOut.println("distTo[" + v + "] = " + distTo[v]);
                // StdOut.println("distTo[" + w + "] = " + distTo[w]);
                return false;
            }
        }
        return true;
    }
}
