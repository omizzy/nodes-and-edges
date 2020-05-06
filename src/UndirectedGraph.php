<?php

namespace TemplesOfCode\NodesAndEdges;

use InvalidArgumentException;

/**
 * Class UndirectedGraph
 * @package TemplesOfCode\NodesAndEdges
 */
class UndirectedGraph extends Graph
{
    /**
     * Add edge v-w to this graph
     *
     * @param int $v
     * @param int $w
     */
    public function addEdge(int $v, int $w)
    {
        // validate the vertex
        Graph::validateVertex($v, $this->vertices);
        // validate the vertex
        Graph::validateVertex($v, $this->vertices);
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
        // fetch from stream
        $first = fgets($handle);
        // fetch from stream
        $second = fgets($handle);
        // parse V and E
        list (
            $vertices,
            $edges
        ) = self::parseGraphVEFromString($first, $second);
        // instantiate a new graph
        $graph = new UndirectedGraph($vertices);
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
        // parse V and E
        list (
            $vertices,
            $edges
        ) = self::parseGraphVEFromString($lines[0], $lines[1]);
        // instantiate a new graph
        $graph = new UndirectedGraph($vertices);
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

    /**
     * @param resource $handle
     * @return UndirectedGraph
     */
    protected static function fromStream($handle)
    {

        // fetch from stream
        $first = fgets($handle);
        // fetch from stream
        $second = fgets($handle);
        // parse V and E
        list (
            $vertices,
            $edges
        ) = self::parseGraphVEFromString($first, $second);
        // instantiate a new graph
        $graph = new UndirectedGraph($vertices);
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
}
