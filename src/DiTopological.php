<?php

namespace NodesAndEdges;

use InvalidArgumentException;

/**
 * Class DiTopological
 *
 * Extracts topological order from a digraph
 *
 * todo: apply this to a EdgeWeightedDigraph
 *
 * @package NodesAndEdges
 */
class DiTopological
{
    /**
     * rank[v] = rank of vertex v in order
     * @var []
     */
    private $rank;

    /** @var array */
    protected $order;

    /**
     * A directed path in a digraph
     * is a sequence of vertices in which
     * there is a (directed) edge pointing
     * from each vertex in the sequence to
     * its successor in the sequence,
     * with no repeated edges.
     *
     * A directed path is simple if it has no repeated vertices.
     *
     * A directed cycle is a directed path
     * (with at least one edge) whose first
     * and last vertices are the same.
     *
     * A directed cycle is simple if
     * it has no repeated vertices
     * (other than the requisite repetition
     * of the first and last vertices).
     *
     * @param Digraph $g
     */
    public function __construct(Digraph $g)
    {
        // delegate cycle finding to type
        $finder = new DirectedCycle($g);
        // see if any were found
        if ($finder->hasCycle()) {
            // there is at least one, we cannot continue
            return;
        }
        // run a dfs on g
        $dfs = new DirectedDepthFirstOrder($g);
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
     * @return array a topological order of the vertices (as an iterable) if the
     *    digraph has a topological order (or equivalently, if the digraph is a DAG),
     *    and null otherwise
     */
    public function order()
    {
        return $this->order;
    }

    /**
     * Does the digraph have a topological order?
     * @return true if the digraph has a topological order (or equivalently,
     *    if the digraph is a DAG), and false otherwise
     */
    public function hasOrder()
    {
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
        $this->validateVertex($vertex);
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

    // throw an IllegalArgumentException unless {@code 0 <= v < V}
    private function validateVertex(int $vertex)
    {
        $vertices = count($this->rank);
        if ($vertex < 0 || $vertex >= $vertices)
            throw new InvalidArgumentException(sprintf(
                'vertex %d is not between 0 and %d',
                $vertex,
                ($vertices-1)
            ));
    }
}