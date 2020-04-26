<?php


namespace NodesAndEdges;

use InvalidArgumentException;

/**
 * Class DirectedCycle
 * @package NodesAndEdges
 */
class DirectedCycle
{

    /** @var array */
    protected $onStack;

    /** @var array */
    protected $cycle;

    /**
     * @var int[]
     */
    private $edgeTo;

    /**
     * @var bool[]
     */
    protected $marked;

    /**
     * @var Graph
     */
    protected $graph;

    /**
     * DirectedCycle constructor.
     * @param Graph $graph
     */
    public function __construct(Graph $graph)
    {
        // set
        $vertices = $graph->getVertices();
        // set
        $this->onStack = array_fill(0, $vertices, false);
        // init
        $this->cycle = null;
        // set
        $this->graph = $graph;
        // set
        $this->marked = array_fill(0, $vertices, false);
        // iterate over the vertices
        for ($vertex = 0; $vertex < $vertices; $vertex++) {
            // check for visit
            if (!$this->marked[$vertex]) {
                // execute DFS logic
                $this->dfs($vertex);
            }
        }
    }

    /**
     * @param int $vertex
     */
    protected function dfs(int $vertex)
    {
        /** @var Digraph $graph */
        $graph = $this->graph;
        // mark the visit
        $this->marked[$vertex] = true;
        // set stack presence
        $this->onStack[$vertex] = true;
        // get neighbors
        $neighbors = $graph->adjacent($vertex);
        // iterate over the set
        foreach ($neighbors as $w) {
            // check for cycles
            if ($this->hasCycle()) {
                // cycle detected
                return;
            } elseif (!$this->marked[$w]) {
                // set
                $this->edgeTo[$w] = $vertex;
                // we have not visited yet
                $this->dfs($w);
            } elseif ($this->onStack[$w]) {
                // we are currently in a path that has visited $w
                $this->cycle = [];
                // iterate over the path
                for ($x = $vertex; $x != $w; $x = $this->edgeTo[$x]) {
                    // add to the end of the list
                    array_unshift($this->cycle, $x);
                }
                // stack the neighbor
                array_unshift($this->cycle, $w);
                // stack the vertex
                array_unshift($this->cycle, $vertex);
            }
        }
        // remove the stack presence
        $this->onStack[$vertex] = false;
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
     * @return bool
     */
    public function hasCycle()
    {
        return !is_null($this->cycle);
    }

    /**
     * @return array|null
     */
    public function cycle()
    {
        return $this->cycle;
    }
}