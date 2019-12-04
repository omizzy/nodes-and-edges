<?php

/**
 *
 */
class Graph
{
    /**
     * @var int
     */
    private $vertices;

    /**
     * @var int
     */
    private $edges;

    /**
     * @var array
     */
    private $adjacencyList;

    /**
     * Create a V-vertex graph with no edges
     *
     * @param int           $vertices       The number of vertices
     * @param array|null    $adjacencyList  Optional prebuilt list
     * @throws InvalidArgumentException
     */
    public function __construct(int $vertices, array $adjacencyList = null)
    {
        // sanity check 
        if ($vertices < 0) {
            // bad state
             throw new InvalidArgumentException(
                'Number of vertices must be nonnegative'
            );
        }
        // init
        $this->edges = 0;
        // set 
        $this->vertices = $vertices;
        // check if prebuilt was passed in
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
        return $this->edges;
    }

    /**
     * Add edge v-w to this graph
     */
    public function addEdge(int $v, int $w)
    {
        // validate the vertex
        static::validateVertex($v, $this->vertices);
        // validate the vertex
        static::validateVertex($v, $this->vertices);
        // bump
        $this->edges++;
        // w is adjacent to v
        array_unshift($this->adjacencyList[$v], $w);
        // v is adjacent to w
        array_unshift($this->adjacencyList[$w], $v);
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
        static::validateVertex($vertex, $this->getVertices());
        // return the adjacent vertices to it
        return $this->adjacencyList[$vertex];
    }

    public function degree(int $vertex)
    {
        // validate the vertex
        static::validateVertex($vertex, $this->getVertices());
        return count($this->adjacent($vertex));
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
     * Initializes a graph from the specified input stream.
     *
     * @param string $in
     * @return Graph
     * @throws InvalidArgumentException
     */
    public static function fromFile(string $in)
    {
        // open the stream for reading
        if (!$handle = fopen($in, 'r')) {
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
                'number of vertices in a Graph must be nonnegative'
            );
        }
        // instantiate a new graph
        $graph = new Graph($vertices);
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
            // read the line and parse
            $edge = explode(' ', fgets($handle));
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

    /**
     * Initializes a new graph that is a deep copy of $g
     */
    public static function fromGraph(Graph $g)
    {
        // get the number of vertices
        $vertices = $g->getVertices();
        // init
        $adjacencyList = [];
        // iterate over the vertices
        for ($vertex = 0; $vertex < $vertices; $vertex++) {
            // get the adjacent vertices
            $adjacencyList[$vertex] = $g->adjacent($vertex);
        }
        // instantiate a new graph
        $graph = new Graph($vertices, $adjacencyList);
        // return the new graph
        return $graph;
    }

    /**
     * @param int $vertex
     * @param int $vertices
     */
    private static function validateVertex(int $vertex, int $vertices)
    {
        // run the check
        if ($vertex < 0 || $vertex >= $vertices) {
            // this vertex is not valid
            throw new IllegalArgumentException(sprintf(
                'vertex %d is not between 0 and %d',
                $vertex,
                $vertices-1
            ));
        }
    }

    public static function main($file)
    {
        $graph = static::fromFile($file);
        print $graph;
    }
}
