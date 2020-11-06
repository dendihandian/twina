@php
    if (isset($graph['edges']) && !empty($graph['edges'])) {
        $graph['links'] = $graph['edges'];
        unset($graph['edges']);
    }

    if ($graph) {
        $graph = [
            'nodes' => array_values($graph['nodes']),
            'links' => array_values($graph['links']),
        ];
    }
@endphp

<script src="https://d3js.org/d3.v6.min.js"></script>
<script>

    var graph = {!! json_encode($graph ?? [], JSON_HEX_TAG) !!};

    if (!graph) {
        var graph = {
            'nodes': [],
            'links': [],
        }
    }

    var svg = d3.select("svg");
    var width = svg.attr("width");
    var height = svg.attr("height");

    var simulation = d3
        .forceSimulation()
        .force(
            "link",
            d3.forceLink().id(function (d) {
                return d.id;
            })
        )
        .force("charge", d3.forceManyBody())

        // center the graph
        .force("center", d3.forceCenter(width / 2, height / 2))
        .force("x", d3.forceX(width / 2))
        .force("y", d3.forceY(height / 2))
        .alpha(0.1).restart();

    if (graph) {
        // force
        //     .nodes(graph.nodes)
        //     .links(graph.links)
        //     .start();

        var link = svg.selectAll(".link")
            .data(graph.links)
            .enter().append("line")
            .attr("class", "link");

        var node = svg.selectAll(".node")
            .data(graph.nodes)
            .enter().append("g")
            .attr("class", "node");
            // .call(force.drag);

        node.append("image")
            .attr("xlink:href", "https://github.com/favicon.ico")
            .attr("x", -8)
            .attr("y", -8)
            .attr("width", 16)
            .attr("height", 16);

        node.append("text")
            .attr("dx", 12)
            .attr("dy", ".35em")
            .text(function(d) { return d.id });

        // force.on("tick", function() {
        //     link.attr("x1", function(d) { return d.source.x; })
        //         .attr("y1", function(d) { return d.source.y; })
        //         .attr("x2", function(d) { return d.target.x; })
        //         .attr("y2", function(d) { return d.target.y; });

        //     node.attr("transform", function(d) { return "translate(" + d.x + "," + d.y + ")"; });
        // });
    }

</script>