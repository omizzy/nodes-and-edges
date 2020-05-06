# nodes-and-edges
A php partial-port of the library described in

https://algs4.cs.princeton.edu/40graphs/ 

[![Build Status](https://travis-ci.org/templesofcode/codesanity.svg?branch=master)](https://travis-ci.org/templesofcode/nodes-and-edges)

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/templesofcode/nodes-and-edges/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/templesofcode/nodes-and-edges/?branch=master)

[![Code Coverage](https://scrutinizer-ci.com/g/templesofcode/nodes-and-edges/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/templesofcode/nodes-and-edges/?branch=master)

[![Code Intelligence Status](https://scrutinizer-ci.com/g/templesofcode/nodes-and-edges/badges/code-intelligence.svg?b=master)](https://scrutinizer-ci.com/code-intelligence)
Example usage:

```php
use TemplesOfCode\NodesAndEdges\BFS\BreadthFirstPaths;
use TemplesOfCode\NodesAndEdges\UndirectedGraph;

function bfsPaths(string $file, int $sourceVertex)
{  
    // build the graph
    $graph = UndirectedGraph::fromFile($file);
    // create an instance
    $bfs = new BreadthFirstPaths($graph, $sourceVertex);
    // iterate over the set of graph vertices
    for ($vertex = 0; $vertex < $graph->getVertices(); $vertex++) {
        // is this connected to the source vertex
        if ($bfs->hasPathTo($vertex)) {
            // print to screen
            print sprintf(
                '%d to %d (%d):  ', 
                $sourceVertex, 
                $vertex,
                $bfs->distTo($vertex)
            );
            // iterate over the path
            foreach ($bfs->pathTo($vertex) as $x) {
                // check for self
                if ($x == $sourceVertex) {
                    print $x;
                } else {
                    print "-" . $x;
                }
            }
            print PHP_EOL;

        } else {
            print sprintf(
                '%d to %d (-):  not connected',
                $sourceVertex,
                $vertex
            );
        }
    }
}
```
