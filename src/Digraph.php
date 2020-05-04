<?php

namespace NodesAndEdges;

use InvalidArgumentException;

/**
 * Class Digraph
 * @package NodesAndEdges
 */
class Digraph extends Graph
{
    /**
     * @var array
     */
    protected $inDegree;

    /**
     * Digraph constructor.
     *
     * @param int $vertices
     * @param array|null $adjacencyList
     */
    public function __construct(int $vertices, array $adjacencyList = null)
    {
        // delegate to parent tasks
        parent::__construct($vertices, $adjacencyList);
        // init
        $this->inDegree = array_fill(0, $vertices, 0);
    }

    /**
     * @param int $v
     * @param int $w
     */
    public function addEdge(int $v, int $w)
    {
        // validate the vertex
        Graph::validateVertex($v, $this->vertices);
        // validate the vertex
        Graph::validateVertex($w, $this->vertices);
        // link the edge to v
        $this->adjacencyList[$v][] = $w;
        // increment the in-degree
        $this->inDegree[$w]++;
        // increment the tracker
        $this->edges++;
    }

    /**
     * Returns the number of directed edges incident from vertex v
     * This is known as the out-degree of vertex v
     *
     * @param int $v the vertex
     * @return int the out-degree of vertex v
     * @throws InvalidArgumentException unless 0 <= v < V
     */
    public function outDegree(int $v)
    {
        // validate
        Graph::validateVertex($v, $this->getVertices());
        // return the count
        return count($this->adjacencyList[$v]);
    }

    /**
     * Returns the number of directed edges incident to vertex v
     * This is known as the in-degree of vertex v
     *
     * @param int $v the vertex
     * @return int the in-degree of vertex v
     * @throws InvalidArgumentException unless 0 <= v < V
     */
    public function inDegree(int $v)
    {
        // validate it
        Graph::validateVertex($v, $this->getVertices());
        // return the count
        return $this->inDegree[$v];
    }

    /**
     * Returns the reverse of the digraph.
     *
     * @return Graph the reverse of the digraph
     */
    public function reverse()
    {
        // get set
        $vertices = $this->getVertices();
        // init
        $reverse = new Digraph($vertices);
        // iterate over the vertices
        for ($vertex = 0; $vertex < $vertices; $vertex++) {
            // get the neighbors
            $neighbors = $this->adjacent($vertex);
            // iterate over the neighbors
            foreach ($neighbors as $w) {
                // add the reverse
                $reverse->addEdge($w, $vertex);
            }
        }
        // return the new digraph
        return $reverse;
    }

    /**
     * Initializes an edge-weighted graph from an input stream.
     * The format is the number of $vertices,
     * followed by the number of $edges ,
     * followed by $edges pairs of vertices and edge weights,
     * with each entry separated by whitespace.
     *
     * @param resource $handle the input stream
     * @return Digraph
     */
    public static function fromStream($handle)
    {
        // sanity check
        if (!is_resource($handle) || $handle === null) {
            // bad state
            throw new InvalidArgumentException(
                'argument is null'
            );
        }
        // parse V and E
        list(
            $vertices,
            $edges
        ) = self::parseGraphVEFromResource($handle);
        // instantiate a new graph
        $graph = new Digraph($vertices);
        // read in the edges
        for ($i = 0; $i < $edges; $i++) {
            // fet from source
            $raw = fgets($handle);
            // parse data
            list (
                $v,
                $w
            ) = self::parseEdge($raw, $vertices);
            // add it to the graph
            $graph->addEdge($v, $w);
        }
        // rewind the stream
        rewind($handle);
        // return the built graph
        return $graph;
    }

    /**
     * Initializes a new digraph that is a deep copy of $g
     *
     * @param Digraph $g
     * @return Digraph
     */
    public static function fromGraph(Digraph $g)
    {
        // make sure there is a references
        if (is_null($g)) {
            // bad state
            throw new InvalidArgumentException('argument is null');
        }
        // get the number of vertices
        $vertices = $g->getVertices();
        // init
        $adjacencyList = [];
        // iterate over the vertices
        for ($vertex = 0; $vertex < $vertices; $vertex++) {
            // get the adjacent vertices
            $adjacencyList[$vertex] = $g->adjacent($vertex);
        }
        // return the new graph
        return new Digraph($vertices, $adjacencyList);
    }

    /**
     *
     * @param string $file
     * @return Digraph
     */
    public static function fromFile(string $file)
    {
        // open the stream for reading
        if (!$handle = fopen($file, 'r')) {
            throw new InvalidArgumentException('could not open stream');
        }
        // parse V and E
        list(
            $vertices,
            $edges
        ) = self::parseGraphVEFromResource($handle);
        // instantiate a new graph
        $graph = new Digraph($vertices);

        // read in the edges
        for ($i = 0; $i < $edges; $i++) {
            // fet from source
            $raw = fgets($handle);
            // parse data
            list (
                $v,
                $w
            ) = self::parseEdge($raw, $vertices);
            // add to the graph
            $graph->addEdge($v, $w);
        }
        // close the stream
        fclose($handle);
        // return the built graph
        return $graph;
    }

    /**
     *
     * @param string $graph
     * @return Digraph
     */
    public static function fromString(string $graph)
    {
        // parse the lines
        $lines = explode("\n", $graph);
        // parse V and E
        list (
            $vertices,
            $edges
        ) = self::parseGraphVEFromString($lines[0], $lines[1]);
        // instantiate a new graph
        $graph = new Digraph($vertices);
        // read in the edges
        for ($i = 0; $i < $edges; $i++) {
            // fet from source
            $raw = $lines[$i+2];
            // parse data
            list (
                $v,
                $w
            ) = self::parseEdge($raw, $vertices);
            // add to the graph
            $graph->addEdge($v, $w);
        }
        // return the built graph
        return $graph;
    }
}