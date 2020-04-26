<?php

namespace NodesAndEdges;

use InvalidArgumentException;

/**
 * Class UndirectedGraph
 * @package NodesAndEdges
 */
class UndirectedGraph extends Graph
{
    /**
     * Add edge v-w to this graph
     * @param int $v
     * @param int $w
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
     * Initializes a graph from the specified input stream.
     *
     * @param string $file
     * @return UndirectedGraph
     * @throws InvalidArgumentException
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
                'number of vertices in a Graph must be nonnegative'
            );
        }
        // instantiate a new graph
        $graph = new UndirectedGraph($vertices);
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
     *
     * @param UndirectedGraph $g
     * @return UndirectedGraph
     */
    public static function fromGraph(UndirectedGraph $g)
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
        // return the new graph
        return new UndirectedGraph($vertices, $adjacencyList);
    }

    /**
     * @param string $graph
     * @return UndirectedGraph
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
        $graph = new UndirectedGraph($vertices);
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
            // read the line and parse
            $edge = explode(' ', $lines[$i+2]);
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
        // return the built graph
        return $graph;
    }

    /**
     * @param resource $handle
     * @return UndirectedGraph
     */
    protected static function fromStream($handle)
    {
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
        $graph = new UndirectedGraph($vertices);
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
}
