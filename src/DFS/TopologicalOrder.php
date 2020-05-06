<?php

namespace TemplesOfCode\NodesAndEdges\DFS;

use InvalidArgumentException;
use TemplesOfCode\NodesAndEdges\Graph;


/**
 * Class TopologicalOrder
 *
 * Extracts topological order from a digraph
 *
 * @package TemplesOfCode\NodesAndEdges\DFS
 */
class TopologicalOrder
{
    /**
     * rank[v] = rank of vertex v in order
     *
     * @var array
     */
    private $rank;

    /**
     * @var array
     */
    protected $order;

    /**
     * @var Graph
     */
    protected $graph;

    /**
     * TopologicalOrder constructor.
     *
     * @param Graph $g
     */
    public function __construct(Graph $g)
    {
        $this->graph = $g;
        // delegate cycle finding to type
        $finder = new DirectedCycle($g);
        // see if any were found
        if ($finder->hasCycle()) {
            // there is at least one, we cannot continue
            return;
        }
        // run a dfs on g
        $dfs = new DepthFirstOrder($g);
        // get the revere order
        $this->order = $dfs->reversePostorder();
        // init the rank list
        $this->rank = array_fill(0, $g->getVertices(), 0);
        // init
        $i = 0;
        // iterate over the
        foreach ($this->order as $vertex) {
            // set
            $this->rank[$vertex] = $i++;
        }
    }


    /**
     * Returns a topological order if the digraph has a topological order,
     * and null otherwise.
     *
     * @return array a topological order of the vertices (as an iterable) if the
     *    digraph has a topological order (or equivalently, if the digraph is a DAG),
     *    and null otherwise
     */
    public function order()
    {
        // return list
        return $this->order;
    }

    /**
     * Does the digraph have a topological order?
     *
     * @return bool true if the digraph has a topological order (or equivalently,
     *    if the digraph is a DAG), and false otherwise
     */
    public function hasOrder()
    {
        // return check
        return !empty($this->order);
    }


    /**
     * The the rank of vertex v in the topological order;
     * -1 if the digraph is not a DAG
     *
     * @param int $vertex the vertex
     * @return int the position of $vertex in a topological order
     *    of the digraph; -1 if the digraph is not a DAG
     * @throws InvalidArgumentException unless 0 <= v < V
     */
    public function rank(int $vertex)
    {
        // validate
        Graph::validateVertex($vertex, $this->graph->getVertices());
        // check for order
        if ($this->hasOrder()) {
            // return the rank
            return $this->rank[$vertex];
        }
        else {
            // is not a DAG
            return -1;
        }
    }
}