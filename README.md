# nodes-and-edges
A php partial-port of the library described in

https://algs4.cs.princeton.edu/40graphs/ 

Example usage:

```php

use NodesAndEdges\BFS\BreadthFirstPaths;

public function bfsPaths(string $file, int $sourceVertex)
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

            foreach ($bfs->pathTo($vertex) as $x) {
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
