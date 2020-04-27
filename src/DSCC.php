<?php


namespace NodesAndEdges;


use InvalidArgumentException;

/**
 * Class DSCC
 *
 * API for Directed Graph Strongly Connected Components
 *
 * @package NodesAndEdges\Command
 */
class DSCC
{
    /**
     * @var bool[]
     */
    protected $marked;

    /**
     * id[v] = id of connected component containing v
     * @var int[]
     */
    protected $id;

    /**
     * number of connected components
     * @var int
     */
    protected $count;

    /**
     * @var Digraph
     */
    protected $graph;

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
        $dfo = new DirectedDepthFirstOrder($g->reverse());
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
        Digraph::validateVertex($vertex, $vertices);
        // return the flag
        return $this->marked[$vertex];
    }

    /**
     * Returns the component id of the connected component containing vertex {@code v}.
     *
     * @param  int $v the vertex
     * @return int the component id of the connected component containing vertex {@code v}
     * @throws InvalidArgumentException unless 0 <= v < V
     */
    public function id(int $v)
    {
        Digraph::validateVertex($v, $this->graph->getVertices());
        return $this->id[$v];
    }

    /**
     * Returns the number of connected components in the graph {@code G}.
     *
     * @return int the number of connected components in the graph {@code G}
     */
    public function count()
    {
        return $this->count;
    }


    /**
     * Depth-first search for an DirectedGraph
     * @param $vertex
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

    /**
     * @param int $v
     * @param int $w
     * @return bool
     */
    public function stronglyConnected(int $v, int $w)
    {
        return $this->id[$v] == $this->id[$w];
    }


}