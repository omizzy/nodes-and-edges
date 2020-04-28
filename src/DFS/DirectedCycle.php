<?php

namespace NodesAndEdges\DFS;

use NodesAndEdges\Graph;

/**
 * Class DirectedCycle
 * @package NodesAndEdges\DFS
 */
class DirectedCycle extends ConnectedComponent
{

    /**
     * @var array
     */
    protected $onStack;

    /**
     * @var null
     */
    protected $cycle;

    /**
     * @var int[]
     */
    private $edgeTo;

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
        // mark the visit
        $this->marked[$vertex] = true;
        // set stack presence
        $this->onStack[$vertex] = true;
        // get neighbors
        $neighbors = $this->graph->adjacent($vertex);
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