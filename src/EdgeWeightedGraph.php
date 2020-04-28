<?php

namespace NodesAndEdges;

use InvalidArgumentException;

/**
 * Class EdgeWeightedGraph
 * @package NodesAndEdges
 */
class EdgeWeightedGraph extends Graph
{
    /**
     * @param Edge $e
     */
    public function addEdge(Edge $e)
    {
        // get one side of edge (a vertex)
        $v = $e->either();
        // get the other side (a vertex)
        $w = $e->other($v);
        // validate the vertex
        Graph::validateVertex($v, $this->vertices);
        // validate the vertex
        Graph::validateVertex($v, $this->vertices);
        // link the edge to v
        $this->adjacencyList[$v][] = $e;
        // link the edge to w
        $this->adjacencyList[$w][] = $e;
        // increment the tracker
        $this->edges++;
    }

    /**
     * Returns all edges in this edge-weighted graph.
     *
     * @return array all edges in this edge-weighted graph, as an iterable
     */
    public function getAllEdges()
    {
        // init
        $allEdges = [];
        // iterate over the set of vertices
        for ($vertex = 0; $vertex < $this->getVertices(); $vertex++) {
            // init
            $selfLoops = 0;
            /** @var array $neighbors */
            $neighbors = $this->adjacencyList[$vertex];
            // iterate over the set of neighbors
            foreach ($neighbors as $neighbor) {
                /** @var Edge $neighbor */
                // get v
                $v = $neighbor->either();
                // get w
                $w = $neighbor->other($v);
                // get weight
                $weight = $neighbor->weight();
                // add to the list
                $allEdges[] = new Edge($v, $w, $weight);
            }
        }
        return $allEdges;
    }

    /**
     * Initializes a graph from the specified file.
     *
     * @param string $in
     * @return EdgeWeightedGraph
     * @throws InvalidArgumentException
     */
    public static function fromFile(string $in)
    {
        // open the file for reading
        if (!$handle = fopen($in, 'r')) {
            throw new InvalidArgumentException('could not open file');
        }
        // read in the amount of vertices (an int) from the stream
        $vertices = (int)filter_var(
            fgets($handle),
            FILTER_SANITIZE_NUMBER_INT
        );
        // sanity check
        if ($vertices < 0) {
            // bad state
            throw new InvalidArgumentException(
                'number of vertices in a Graph must be non-negative'
            );
        }
        // instantiate a new graph
        $graph = new EdgeWeightedGraph($vertices);
        // read in the amount of edges in the stream
        $edges = (int)filter_var(
            fgets($handle),
            FILTER_SANITIZE_NUMBER_INT
        );
        // sanity check
        if ($edges < 0) {
            // bad state
            throw new InvalidArgumentException(
                'number of edges in a Graph must be nonnegative'
            );
        }
        // read in the edges
        for ($i = 0; $i < $edges; $i++) {
            // fet from source
            $raw = fgets($handle);
            // clean
            $trimmed = trim($raw);
            // parse
            $exploded = explode(' ', $trimmed);
            // filter
            $filtered = array_filter($exploded, function($v, $k) {
                // make sure it valid
                return (!empty($v) || (strlen($v) > 0));
            }, ARRAY_FILTER_USE_BOTH);
            // get values
            $edge = array_values($filtered);
            // get v
            $v = (int)filter_var(
                $edge[0],
                FILTER_SANITIZE_NUMBER_INT
            );
            // get w
            $w = (int)filter_var(
                $edge[1],
                FILTER_SANITIZE_NUMBER_INT
            );
            // validate it
            Graph::validateVertex($v, $vertices);
            // validate it
            Graph::validateVertex($w, $vertices);
            // get weight
            $weight = (int)filter_var(
                $edge[2],
                FILTER_SANITIZE_NUMBER_INT
            );
            // re-use var here
            $edge = new Edge($v, $w, $weight);
            // add to the graph
            $graph->addEdge($edge);
        }
        // close the stream
        fclose($handle);
        // return the built graph
        return $graph;
    }

    /**
     * Initializes a new graph that is a deep copy of $g
     *
     * @param EdgeWeightedGraph $g
     * @return EdgeWeightedGraph
     */
    public static function fromGraph(EdgeWeightedGraph $g)
    {
        // get the number of vertices
        $vertices = $g->getVertices();
        // init
        $adjacencyList = [];
        // iterate over the vertices
        for ($vertex = 0; $vertex < $vertices; $vertex++) {
            // get the adjacent vertices
            $neighbors = $g->adjacent($vertex);
            // init
            $myAdjacencyList = [];
            // iterate over them
            foreach ($neighbors as $edge) {
                /** @var Edge $e */
                // get one side of edge (a vertex)
                $v = $edge->either();
                // get the other side (a vertex)
                $w = $edge->other($v);
                // get the weight
                $weight = $edge->weight();
                // create a new edge and set it
                $e = new Edge($v, $w, $weight);
                // add the edge to the list
                $myAdjacencyList[] = $e;
            }
            // set this set
            $adjacencyList[$vertex] = $myAdjacencyList;
        }
        // return the new graph
        return new EdgeWeightedGraph($vertices, $adjacencyList);
    }

    /**
     * @param string $graph
     * @return EdgeWeightedGraph
     */
    public static function fromString(string $graph)
    {
        // parse the lines
        $lines = explode("\n", $graph);
        // open the stream for reading
        $vertices = (int)filter_var(
            $lines[0],
            FILTER_SANITIZE_NUMBER_INT
        );
        // sanity check
        if ($vertices < 0) {
            // bad state
            throw new InvalidArgumentException(
                'number of vertices in a Graph must be nonnegative'
            );
        }
        // instantiate a new graph
        $graph = new EdgeWeightedGraph($vertices);
        // read in the amount of edges in the stream
        $edges = (int)filter_var(
            $lines[1],
            FILTER_SANITIZE_NUMBER_INT
        );
        // sanity check
        if ($edges < 0) {
            // bad state
            throw new InvalidArgumentException(
                'number of edges in a Graph must be non-negative'
            );
        }
        // read in the edges
        for ($i = 0; $i < $edges; $i++) {
            // fet from source
            $raw = $lines[$i+2];
            // clean
            $trimmed = trim($raw);
            // parse
            $exploded = explode(' ', $trimmed);
            // filter
            $filtered = array_filter($exploded, function($v, $k) {
                // make sure it valid
                return (!empty($v) || (strlen($v) > 0));
            }, ARRAY_FILTER_USE_BOTH);
            // get values
            $edge = array_values($filtered);
            // get v
            $v = (int)filter_var(
                $edge[0],
                FILTER_SANITIZE_NUMBER_INT
            );
            // get w
            $w = (int)filter_var(
                $edge[1],
                FILTER_SANITIZE_NUMBER_INT
            );
            // validate it
            Graph::validateVertex($v, $vertices);
            // validate it
            Graph::validateVertex($w, $vertices);
            // set a default
            $weight = 0;
            // get weight
            if (!empty($edge[2])) {
                $weight = (int)filter_var(
                    $edge[2],
                    FILTER_SANITIZE_NUMBER_INT
                );
            }
            // re-use var
            $edge = new Edge($v, $w, $weight);
            // add to the graph
            $graph->addEdge($edge);
        }
        // return the built graph
        return $graph;
    }

    /**
     * Initializes a random edge-weighted graph with $v vertices and $e edges.
     *
     * @param int $vertices the number of vertices
     * @param int $edges the number of edges
     * @return EdgeWeightedGraph
     */
    public static function fromRandom(int $vertices, int $edges)
    {
        // sanity check
        if ($edges < 0) {
            // not acceptable
            throw new InvalidArgumentException(
                'Number of edges must be non-negative'
            );
        }
        // instantiate a new graph
        $graph = new EdgeWeightedGraph($vertices);
        // init
        $taken = [];
        // iterate over the edges
        for ($i = 0; $i <$edges; $i++) {
            // generate an edge
            do {
                // generate
                $v = mt_rand(0, $vertices);
                // generate
                $w = mt_rand(0, $vertices);
                // check
                $pairTaken = in_array(
                    sprintf('%d-%d', $v, $w),
                    $taken
                );
            } while ($v == $w && !$pairTaken);
            // add to the set
            $taken[] = $pairTaken;
            // generate weight
            $weight = ((float)(mt_rand(0, 100)));
            // create the edge
            $edge = new Edge($v, $w, $weight);
            // add it to the graph
            $graph->addEdge($edge);
        }
        // return the graph
        return $graph;
    }

    /**
     * Initializes an edge-weighted graph from an input stream.
     * The format is the number of $vertices,
     * followed by the number of $edges ,
     * followed by $edges pairs of vertices and edge weights,
     * with each entry separated by whitespace.
     *
     * @param resource $handle the input stream
     * @return EdgeWeightedGraph
     */
    protected static function fromStream($handle)
    {
        // sanity check
        if (!is_resource($handle) || $handle === null) {
            // bad state
            throw new InvalidArgumentException(
                'argument is null'
            );

        }
        // read in the amount of vertices (an int) from the stream
        $vertices = (int)filter_var(
            fgets($handle),
            FILTER_SANITIZE_NUMBER_INT
        );
        // sanity check
        if ($vertices < 0) {
            // bad state
            throw new InvalidArgumentException(
                'number of vertices in a Graph must be non-negative'
            );
        }
        // instantiate a new graph
        $graph = new EdgeWeightedGraph($vertices);
        // read in the amount of edges in the stream
        $edges = (int)filter_var(
            fgets($handle),
            FILTER_SANITIZE_NUMBER_INT
        );
        // sanity check
        if ($edges < 0) {
            // bad state
            throw new InvalidArgumentException(
                'number of edges in a Graph must be non-negative'
            );
        }
        // read in the edges
        for ($i = 0; $i < $edges; $i++) {
            // fet from source
            $raw = fgets($handle);
            // clean
            $trimmed = trim($raw);
            // parse
            $exploded = explode(' ', $trimmed);
            // filter
            $filtered = array_filter($exploded, function($v, $k) {
                // make sure it valid
                return (!empty($v) || (strlen($v) > 0));
            }, ARRAY_FILTER_USE_BOTH);
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
            // get weight
            $weight = (int)filter_var(
                $edge[2],
                FILTER_SANITIZE_NUMBER_INT
            );
            // create the edge
            $edge = new Edge($v, $w, $weight);
            // add it to the graph
            $graph->addEdge($edge);
        }
        // rewind the stream
        rewind($handle);
        // return the built graph
        return $graph;
    }
}
