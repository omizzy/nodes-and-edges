<?php

namespace TemplesOfCode\NodesAndEdges\DFS;

use TemplesOfCOde\NodesAndEdges\Digraph;
use TemplesOfCOde\NodesAndEdges\Graph;

/**
 * Class DSCC
 *
 * API for Directed Graph Strongly Connected Components
 *
 * @package TemplesOfCode\TemplesOfCOde\NodesAndEdges\DFS
 */
class DSCC extends ConnectedComponent
{
    /**
     * Computes the connected components of the undirected graph g.
     *
     * @param Digraph $g the graph
     */
    public function __construct(Digraph $g)
    {
        // init
        $this->count = 0;
        // get vertices
        $vertices = $g->getVertices();
        // set
        $this->graph = $g;
        // init
        $this->marked = array_fill(0, $vertices, false);
        // init
        $this->id = array_fill(0, $vertices, null);
        // build a dfo
        $dfo = new DepthFirstOrder($g->reverse());
        // get the order
        $reversePostorder = $dfo->reversePostorder();
        // iterate over the set
        foreach ($reversePostorder as $s) {
            // check if we have touched this vertex
            if (!$this->marked[$s]) {
                // run it
                $this->dfs($s);
                // increment the count for components
                $this->count++;
            }
        }
    }

    /**
     * @param int $v
     * @param int $w
     * @return bool
     */
    public function stronglyConnected(int $v, int $w)
    {
        // delegate to default
        return $this->connected($v, $w);
    }

    /**
     * Depth-first search for an DirectedGraph
     * @param int $vertex
     */
    protected function dfs(int $vertex)
    {
        // we have visited now
        $this->marked[$vertex] = true;
        // set the component #
        $this->id[$vertex] = $this->count;
        // get the neighbors
        $neighbors = $this->graph->adjacent($vertex);
        // iterate over the neighbors
        foreach ($neighbors as $w) {
            // check if we have visited
            if (!$this->marked[$w]) {
                // lets visit
                $this->dfs($w);
            }
        }
    }
}