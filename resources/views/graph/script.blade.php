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

<script src="https://d3js.org/d3.v4.min.js"></script>
<script>
    var graph = {!! json_encode($graph ?? [], JSON_HEX_TAG) !!};
    console.log('graph', graph);
</script>
<script>

    if (typeof(graph) === 'undefined') {
        var graph = {
            'nodes': [],
            'links': [],
        } 
    }

    // console.log('graph', graph);

    var svg = d3.select("svg");
    var width = svg.attr("width");
    var height = svg.attr("height");
    var defaultNodeRadius = 5;

    var color = d3.scaleOrdinal(d3.schemeCategory20);

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

    var link = svg
        .append("g")
        .attr("class", "links")
        .selectAll("line")
        .data(graph.links)
        .enter()
        .append("line")
        .style("stroke-width", function (d) {
            return Math.sqrt(d.value);
        });

    // var setEventsLink = link.
    //         on( 'mouseenter', function() {
    //             d3.select( this )
    //                 .selectAll("text")
    //                 .style('display', 'block');
    //         })
    //         .on( 'mouseleave', function() {
    //             d3.select( this )
    //                 .selectAll("text")
    //                 .style('display', 'none');
    //         });

    var node = svg
        .append("g")
        .attr("class", "nodes")
        .selectAll("g")
        .data(graph.nodes)
        .enter()
        .append("g");

    var imageRes = 20;
    var circles = node
        .append("circle")
        .attr("r", defaultNodeRadius) // radius or circle size
        .attr("fill", function (d) {
            // console.log('color(d.group)', color(d.group));
            // return color(d.group);
            // return color(d.verified ? '#555' : '#aaa');
            // return '#2ca02c';
            // return '#ff7f0e';
            if (d.deleted) {
                return 'red';
            } else {
                return (d.verified ? 'blue' : 'green');
            }
        })
        .call(
            d3
            .drag()
            .on("start", dragstarted)
            .on("drag", dragged)
            .on("end", dragended)
        );
        // .append("image")
        //     .attr("xlink:href",  function(d) { return d.img;})
        //     .attr("x", function(d) { return -(imageRes / 2);})
        //     .attr("y", function(d) { return -(imageRes / 2);})
        //     // .attr("style", "border-radius: 50%;")
        //     .style("border-radius", '50%')
        //     .attr("height", imageRes)
        //     .attr("width", imageRes);


    // var images = node.append("svg:image")
    //         .attr("xlink:href",  function(d) { return d.img;})
    //         .attr("x", function(d) { return -(imageRes / 2);})
    //         .attr("y", function(d) { return -(imageRes / 2);})
    //         // .attr("style", "border-radius: 50%;")
    //         .style("border-radius", '50%')
    //         .style("pointer-events", 'none')
    //         .style("user-select", 'none')
    //         .attr("height", imageRes)
    //         .attr("width", imageRes);

    var nodeLabels = node
        .append("text")
        .text(function (d) {
            if (d.screen_name) {
                return d.screen_name;
            } else {
                return d.id;
            }
        })
        .style('display', 'none')
        .attr("x", 12)
        .attr("y", 3);

    var linkLabels = link
        .append("text")
        .attr("y", 12)
        .attr("x", function(){ return 12;})
        .style('display', 'block')
        .text("I'm a label")
        .attr('font-size', 10);

    var setEventsNode = node.
            on( 'mouseenter', function() {
                d3.select( this )
                    .selectAll("text")
                    .style('display', 'block');

                d3.select(this)
                    .selectAll('circle')
                    .attr('r', 10);
            })
            .on( 'mouseleave', function() {
                d3.select( this )
                    .selectAll("text")
                    .style('display', 'none');

                d3.select(this)
                    .selectAll('circle')
                    .attr('r', defaultNodeRadius);
            });

    var setEventsLink = link.
            on( 'mouseenter', function() {
                console.log('mpe');
                d3.select( this )
                    .selectAll("text")
                    .style('display', 'block');
            })
            .on( 'mouseleave', function() {
                console.log('lpe');
                d3.select( this )
                    .selectAll("text")
                    .style('display', 'none');
            });


    node.append("title").text(function (d) {
        return d.screen_name;
    });

    simulation.nodes(graph.nodes).on("tick", ticked);

    simulation.force("link").links(graph.links);

    function ticked() {
        link
            .attr("x1", function (d) {
            return d.source.x;
            })
            .attr("y1", function (d) {
            return d.source.y;
            })
            .attr("x2", function (d) {
            return d.target.x;
            })
            .attr("y2", function (d) {
            return d.target.y;
            });

        node.attr("transform", function (d) {
            return "translate(" + d.x + "," + d.y + ")";
        });
    }

    function dragstarted(d) {
        if (!d3.event.active) simulation.alphaTarget(0.3).restart();

        d.fx = d.x;
        d.fy = d.y;
    }

    function dragged(d) {
        d.fx = d3.event.x;
        d.fy = d3.event.y;
    }

    function dragended(d) {
        if (!d3.event.active) simulation.alphaTarget(0);

        d.fx = null;
        d.fy = null;
    }
</script>