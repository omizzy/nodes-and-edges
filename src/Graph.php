<?php

namespace NodesAndEdges;

use InvalidArgumentException;

/**
 * Class Graph
 * @package NodesAndEdges
 */
abstract class Graph
{
    /**
     * @var int
     */
    protected $vertices;

    /**
     * @var int
     */
    protected $edges;

    /**
     * @var array
     */
    protected $adjacencyList;

    /**
     * Initializes an empty edge-weighted graph with {@code V} vertices and 0 edges.
     *
     * @param int $vertices
     * @param array|null $adjacencyList
     */
    public function __construct(int $vertices, array $adjacencyList = null)
    {
        //
        if ($vertices < 0) {
            throw new InvalidArgumentException(
                'Number of vertices must be non-negative'
            );
        }
        // set
        $this->vertices = $vertices;
        // init
        $this->edges = 0;
        // get the ne
        if (!empty($adjacencyList)) {
            // set it
            $this->adjacencyList= $adjacencyList;
        } else {
            // init
            $this->adjacencyList = [];
            // iterate over the set of vertices
            for ($vertex = 0; $vertex < $vertices; $vertex++) {
                // initialize each vertex adjacency list
                $this->adjacencyList[$vertex] = [];
            }
        }
    }

    /**
     * Returns the number of vertices in this graph.
     *
     * @return int
     */
    public function getVertices()
    {
        // return the amount
        return $this->vertices;
    }

    /**
     * Returns the number of edges in this graph.
     *
     * @return int
     */
    public function getEdges()
    {
        // return the number of edges
        return $this->edges;
    }

    /**
     * Returns the vertices adjacent to $vertex
     *
     * @param int $vertex
     * @return array
     */
    public function adjacent(int $vertex)
    {
        // validate the vertex
        Digraph::validateVertex($vertex, $this->getVertices());
        // return the adjacent vertices to it
        return $this->adjacencyList[$vertex];
    }

    /**
     * @param int $vertex
     * @return int
     */
    public function degree(int $vertex)
    {
        // validate the vertex
        Digraph::validateVertex($vertex, $this->getVertices());
        // return the count of neighbors
        return count($this->adjacent($vertex));
    }

    /**
     * Utility function
     *
     * @param int $vertex
     * @param int $vertices
     */
    public static function validateVertex(int $vertex, int $vertices)
    {
        // run the check
        if ($vertex < 0 || $vertex >= $vertices) {
            // this vertex is not valid
            throw new InvalidArgumentException(sprintf(
                'vertex %d is not between 0 and %d',
                $vertex,
                $vertices - 1
            ));
        }
    }

    /**
     * Returns a string representation of this graph.
     */
    public function __toString()
    {
        $vertices = $this->getVertices();
        // init
        $buffer = [];
        // add
        $buffer[] = sprintf(
            '%d vertices, %d edges',
            $vertices,
            $this->getEdges()
        );
        // iterate over the vertices
        for ($vertex = 0; $vertex < $vertices; $vertex++) {
            // get the adjacent vertices
            $adjacentVertices = $this->adjacent($vertex);
            // add
            $buffer[] = sprintf(
                '%d : %s',
                $vertex,
                implode(' ', $adjacentVertices)
            );
        }
        // convert to string
        return implode(PHP_EOL, $buffer);
    }
}