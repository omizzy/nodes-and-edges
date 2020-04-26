<?php


namespace NodesAndEdges;

use InvalidArgumentException;

/**
 * Class Digraph
 * @package NodesAndEdges
 */
class Digraph extends Graph
{
    /** @var array */
    protected $inDegree;

    /**
     * Digraph constructor.
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
        static::validateVertex($v, $this->vertices);
        // validate the vertex
        static::validateVertex($w, $this->vertices);
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
     * @param  int $v the vertex
     * @return int the out-degree of vertex v
     * @throws InvalidArgumentException unless 0 <= v < V
     */
    public function outDegree(int $v)
    {
        // validate
        static::validateVertex($v, $this->getVertices());
        // return the count
        return count($this->adjacencyList[$v]);
    }

    /**
     * Returns the number of directed edges incident to vertex v
     * This is known as the in-degree of vertex v
     *
     * @param  int $v the vertex
     * @return int the in-degree of vertex v
     * @throws InvalidArgumentException unless 0 <= v < V
     */
    public function inDegree(int $v)
    {
        // validate it
        static::validateVertex($v, $this->getVertices());
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
        $graph = new Digraph($vertices);
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
            // read the line and parse
            $edge = explode(' ', fgets($handle));
            // get v
            $v = (int)filter_var(
                $edge[0],
                FILTER_SANITIZE_NUMBER_INT
            );
            // validate it
            static::validateVertex($v, $vertices);
            // get w
            $w = (int)filter_var(
                $edge[1],
                FILTER_SANITIZE_NUMBER_INT
            );
            // validate it
            static::validateVertex($w, $vertices);
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
        $graph = new Digraph($vertices);
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
            // read the line and parse
            $edge = array_values(
                array_filter(
                    explode(
                        ' ',
                        trim(fgets($handle))
                    )
                )
            );
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
            static::validateVertex($v, $vertices);
            // validate it
            static::validateVertex($w, $vertices);
            // add to the graph
            $graph->addEdge($v, $w);
        }
        // close the stream
        fclose($handle);
        // return the built graph
        return $graph;
    }
}