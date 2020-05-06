<?php

namespace TemplesOfCode\NodesAndEdges;

use InvalidArgumentException;

/**
 * Class Edge
 * @package TemplesOfCode\NodesAndEdges
 */
class Edge
{
    /**
     * @var int
     */
    private $v;

    /**
     * @var int
     */
    private $w;

    /**
     * @var float
     */
    private $weight;

    /**
     * Initializes an edge between vertices $v and $w of the given $weight
     *
     * @param int $v one vertex
     * @param int $w the other vertex
     * @param float $weight the weight of this edge
     * @throws InvalidArgumentException if either $v or $w is a negative integer or if $weight is NaN
     */
    public function __construct(int $v, int $w, float $weight = 0.0)
    {
        // sanity check
        if ($v < 0) {
            // panic here
            throw new InvalidArgumentException("vertex index must be a non-negative integer");
        }
        // sanity check
        if ($w < 0) {
            // panic here
            throw new InvalidArgumentException("vertex index must be a non-negative integer");
        }
        // sanity check
        if (is_nan($weight)) {
            // panic here
            throw new InvalidArgumentException("Weight is NaN");
        }
        // set
        $this->v = $v;
        // set
        $this->w = $w;
        // set
        $this->weight = $weight;
    }

    /**
     * Returns the weight of this edge.
     *
     * @return float the weight of this edge
     */
    public function weight()
    {
        return $this->weight;
    }

    /**
     * Returns either endpoint of this edge.
     *
     * @return int either endpoint of this edge
     */
    public function either()
    {
        return $this->v;
    }

    /**
     * Returns the endpoint of this edge that is different from the given vertex.
     *
     * @param int $vertex one endpoint of this edge
     * @return int the other endpoint of this edge
     */
    public function other(int $vertex)
    {
        // check what side are we on and return the other
        if ($vertex == $this->v) {
            // return it
            return $this->w;
        } else if ($vertex == $this->w) {
            // return it
            return $this->v;
        } else {
            // no go
            throw new InvalidArgumentException("Illegal endpoint");
        }
    }

    /**
     * Compares two edges by weight.
     *
     * @param  Edge the other edge
     * @return int a negative integer, zero, or positive integer depending on whether
     *  the weight of this is less than, equal to, or greater than the
     *  argument edge
     */
    public function compareTo(Edge $that)
    {
        // get
        $weight = $this->weight();
        // get
        $otherWeight = $that->weight();
        // resolve
        if ($weight < $otherWeight) {
            // return less than
            return -1;
        } else if ($weight == $otherWeight){
            // return equal
            return 0;
        } else {
            // return greater than
            return 1;
        }
    }

    /**
     * Returns a string representation of this edge.
     *
     * @return string a string representation of this edge
     */
    public function __toString()
    {
        // return formatted
        return sprintf("%d-%d %.5f", $this->v, $this->w, $this->weight);
    }
}
