<?php


namespace TemplesOfCode\NodesAndEdges\DFS;

use TemplesOfCOde\NodesAndEdges\Graph;

/**
 * Class DepthFirstOrder
 * @package TemplesOfCOde\NodesAndEdges
 */
class DepthFirstOrder
{
    /**
     * @var bool[]
     */
    protected $marked;

    /**
     * @var Graph
     */
    protected $graph;

    /**
     * @var array
     */
    private $pre;

    /**
     * @var array
     */
    private $post;

    /**
     * @var array
     */
    private $reversePost;

    /**
     * DepthFirstOrder constructor.
     *
     * @param Graph $graph
     */
    public function __construct(Graph $graph)
    {
        // init
        $this->pre = [];
        // init
        $this->post = [];
        // init
        $this->reversePost = [];
        // set
        $this->graph = $graph;
        // set
        $vertices = $graph->getVertices();
        // set
        $this->marked = array_fill(0, $vertices, false);
        // iterate over the vertices
        for ($vertex = 0; $vertex < $vertices; $vertex++) {
            // check for visit
            if (!$this->marked[$vertex]) {
                // execute DFS logic
                $this->dfs($vertex);
            }
        }
    }

    /**
     * @param int $vertex
     */
    protected function dfs(int $vertex)
    {
        // enqueue $vertex - add to end
        array_push($this->pre, $vertex);
        // mark the visit
        $this->marked[$vertex] = true;
        // get neighbors
        $neighbors = $this->graph->adjacent($vertex);
        // iterate over the set
        foreach ($neighbors as $w) {
            // check for previous visit
            if (!$this->marked[$w]) {
                // has not been visited yet
                $this->dfs($w);
            }
        }
        // enqueue $vertex - add to end
        array_push($this->post, $vertex);
        // push to the reverse post stack - add to beginning
        array_unshift($this->reversePost, $vertex);
    }

    /**
     * @return array
     */
    public function preorder()
    {
        return $this->pre;
    }

    /**
     * @return array
     */
    public function postorder()
    {
        return $this->post;
    }

    /**
     * @return array
     */
    public function reversePostorder()
    {
       return $this->reversePost;
    }
}