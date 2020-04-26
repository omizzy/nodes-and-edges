<?php

namespace NodesAndEdges;

use InvalidArgumentException;

/**
 * Class ConnectedComponent
 * @package NodesAndEdges
 */
abstract class ConnectedComponent
{
    /**
     * id[v] = id of connected component containing v
     * @var int[]
     */
    protected $id;

    /**
     * marked[v] = has vertex v been marked?
     * @var int[]
     */
    protected $marked;

    /**
     * size[id] = number of vertices in given component
     * @var int[]
     */
    protected $size;

    /**
     * number of connected components
     * @var int
     */
    protected $count;

    /**
     * Computes the connected components of the undirected graph g.
     *
     * @param Graph $g the graph
     */
    public function __construct(Graph $g)
    {
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
                $this->dfs($g, $vertex);
                // increment the count for components
                $this->count++;
            }
        }
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
        $this->validateVertex($v);
        return $this->id[$v];
    }


    /**
     * Returns the number of vertices in the connected component containing vertex {@code v}.
     *
     * @param  int $v the vertex
     * @return int the number of vertices in the connected component containing vertex {@code v}
     * @throws InvalidArgumentException unless 0 <= v < V
     */
    public function size(int $v)
    {
        $this->validateVertex($v);
        return $this->size[$this->id($v)];
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
     * Returns true if vertices $v and $w are in the same
     * connected component.
     *
     * @param  int $v one vertex
     * @param  int $w the other vertex
     * @return  true if vertices v and w are in the same
     *         connected component; false otherwise
     * @throws InvalidArgumentException unless 0 <= v < V
     * @throws InvalidArgumentException unless  0 <= w < V
     */
    public function connected(int $v, int $w)
    {
        $this->validateVertex($v);
        $this->validateVertex($w);
        return $this->id($v) == $this->id($w);
    }

    // throw an IllegalArgumentException unless 0 <= v < V
    protected function validateVertex(int $vertex)
    {
        $vertices = count($this->marked);
        if ($vertex < 0 || $vertex >= $vertices) {
            throw new InvalidArgumentException(sprintf(
                'vertex %d is not between 0 and %d',
                $vertex,
                ($vertices - 1)
            ));
        }
    }

/**
     * @param Graph $g
     * @param int $vertex
     * @return mixed
     */
    abstract protected function dfs($g, int $vertex);
}
