<?php

namespace TemplesOfCode\NodesAndEdges\DFS;

use InvalidArgumentException;
use TemplesOfCode\NodesAndEdges\Graph;

/**
 * Class ConnectedComponent
 * @package TemplesOfCode\NodesAndEdges\DFS
 */
abstract class ConnectedComponent
{
    /**
     * id[v] = id of connected component containing v
     *
     * @var int[]
     */
    protected $id;

    /**
     * marked[v] = has vertex v been marked?
     *
     * @var int[]
     */
    protected $marked;

    /**
     * size[id] = number of vertices in given component
     *
     * @var int[]
     */
    protected $size;

    /**
     * number of connected components
     *
     * @var int
     */
    protected $count;

    /**
     * @var Graph
     */
    protected $graph;

    /**
     * Computes the connected components of the graph g.
     *
     * @param Graph $g the graph
     */
    public function __construct(Graph $g)
    {
        // set
        $this->graph = $g;
        // get vertices
        $vertices = $g->getVertices();
        // init
        $this->marked = array_fill(0, $vertices, false);
        // init
        $this->id = array_fill(0, $vertices, 0);
        // init
        $this->size = array_fill(0, $vertices, 0);
        // init
        $this->count = 0;
        // iterate over the set
        for ($vertex = 0; $vertex < $vertices; $vertex++) {
            // check if we have touched this vertex
            if (!$this->marked[$vertex]) {
                // we haven't, lets explore
                $this->dfs($vertex);
                // increment the count for components
                $this->count++;
            }
        }
    }

    /**
     * Returns the component id of the connected component containing vertex $v.
     *
     * @param int $v the vertex
     * @return int the component id of the connected component containing vertex $v
     * @throws InvalidArgumentException unless 0 <= $v < V
     */
    public function id(int $v)
    {
        // validate
        Graph::validateVertex($v, $this->graph->getVertices());
        // fetch and return
        return $this->id[$v];
    }


    /**
     * Returns the number of vertices in the connected component containing $v
     *
     * @param int $v the vertex
     * @return int the number of vertices in the connected component containing $v
     * @throws InvalidArgumentException unless 0 <= v < V
     */
    public function size(int $v)
    {
        // validate
        Graph::validateVertex($v, $this->graph->getVertices());
        // fetch and return
        return $this->size[$this->id($v)];
    }


    /**
     * Returns the number of connected components in the graph
     *
     * @return int the number of connected components in the graph
     */
    public function count()
    {
        // fetch and return
        return $this->count;
    }

    /**
     * Returns true if vertices $v and $w are in the same
     * connected component.
     *
     * @param  int $v one vertex
     * @param  int $w the other vertex
     * @return true if vertices v and w are in the same
     *         connected component; false otherwise
     * @throws InvalidArgumentException unless 0 <= v < V
     * @throws InvalidArgumentException unless  0 <= w < V
     */
    public function connected(int $v, int $w)
    {
        // validate
        Graph::validateVertex($v, $this->graph->getVertices());
        // validate
        Graph::validateVertex($w, $this->graph->getVertices());
        // evaluate
        return $this->id($v) == $this->id($w);
    }


    /**
     * Is there a path between the source vertex and vertex v
     *
     * @param int $vertex
     * @return bool true if there is a path, false otherwise
     * @throws InvalidArgumentException unless 0 <= $vertex < $vertices
     */
    public function marked(int $vertex)
    {
        // convenience var
        $vertices = $this->graph->getVertices();
        // validate this vertex in the context of the given graph
        Graph::validateVertex($vertex, $vertices);
        // return the flag
        return $this->marked[$vertex];
    }

    /**
     * @param int $vertex
     * @return mixed
     */
    abstract protected function dfs(int $vertex);
}
