<?php

namespace TemplesOfCode\NodesAndEdges;

use InvalidArgumentException;

/**
 * Class Graph
 * @package TemplesOfCode\NodesAndEdges
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

    /**
     * @param string $input
     * @param int $vertices
     * @param bool $weight
     * @return array
     */
    protected static function parseEdge(string $input,int $vertices, bool $weight = false)
    {
        // clean
        $trimmed = trim($input);
        // parse
        $exploded = explode(' ', $trimmed);
        // filter
        $filtered = array_filter($exploded, function($vertex) {
            // make sure it valid
            return (!empty($vertex) || (strlen($vertex) > 0));
        });
        // get values
        $edge = array_values($filtered);
        // get v
        $v = (int)filter_var(
            $edge[0],
            FILTER_SANITIZE_NUMBER_INT
        );
        // validate it
        Graph::validateVertex($v, $vertices);
        // get w
        $w = (int)filter_var(
            $edge[1],
            FILTER_SANITIZE_NUMBER_INT
        );
        // validate it
        Graph::validateVertex($w, $vertices);
        // create set
        $set = [
            $v,
            $w,
        ];
        // check if weight needs to be added
        if ($weight) {
            // get weight
            $inputWeight = (int)filter_var(
                $edge[2],
                FILTER_SANITIZE_NUMBER_INT
            );
            // add it to the set
            $set[] = $inputWeight;
        }
        // return set
        return $set;
    }

    /**
     * @param resource $handle
     * @return int[]
     */
    protected static function parseGraphVEFromResource($handle)
    {
        // read from stream
        $first = fgets($handle);
        // read from stream
        $second = fgets($handle);
        // delegate to string parser
        return self::parseGraphVEFromString(
            $first,
            $second
        );
    }

    /**
     * @param string $first
     * @param string $second
     * @return int[]
     */
    protected static function parseGraphVEFromString(string $first, string $second)
    {
        // open the stream for reading
        $vertices = (int)filter_var(
            $first,
            FILTER_SANITIZE_NUMBER_INT
        );
        // sanity check
        if ($vertices < 0) {
            // bad state
            throw new InvalidArgumentException(
                'number of vertices in a Graph must be non-negative'
            );
        }

        // read in the amount of edges in the stream
        $edges = (int)filter_var(
            $second,
            FILTER_SANITIZE_NUMBER_INT
        );
        // sanity check
        if ($edges < 0) {
            // bad state
            throw new InvalidArgumentException(
                'number of edges in a Graph must be non-negative'
            );
        }
        // return the set
        return [
            $vertices,
            $edges
        ];
    }

    /**
     * @param Graph $graph
     * @param int $vertices
     * @param int $edges
     * @param resource $handle
     */
    protected static function buildWeightedEdgesFromHandle(
        Graph $graph,
        int $vertices,
        int $edges,
        $handle
    ) {
        // read in the edges
        for ($i = 0; $i < $edges; $i++) {
            // fet from source
            $raw = fgets($handle);
            // parse data
            list (
                $v,
                $w,
                $weight
                ) = self::parseEdge($raw, $vertices, true);
            // re-use var here
            $edge = new Edge($v, $w, $weight);
            // add to the graph
            $graph->addEdge($edge);
        }
    }

    /**
     * @param Graph $graph
     * @param int $vertices
     * @param int $edges
     * @param array $lines
     */
    protected static function buildWeightedEdgesFromString(
        Graph $graph,
        int $vertices,
        int $edges,
        array $lines
    ) {
        // read in the edges
        for ($i = 0; $i < $edges; $i++) {
            // fet from source
            $raw = $lines[$i];
            // parse data
            list (
                $v,
                $w,
                $weight
            ) = self::parseEdge($raw, $vertices, true);
            // re-use var
            $edge = new Edge($v, $w, $weight);
            // add to the graph
            $graph->addEdge($edge);
        }
    }
}