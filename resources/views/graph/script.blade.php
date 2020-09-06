<script src="https://d3js.org/d3.v4.min.js"></script>
<script>
    var svg = d3.select("svg");
    console.log('svg', svg.attr('width'));
    var width = svg.attr("width");
    var height = svg.attr("height");

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
        .force("center", d3.forceCenter(width / 2, height / 2));

    var url =
        "https://gist.githubusercontent.com/dendihandian/7c2e187a5a71ba3011c767d97f95eb8f/raw/e0cea3a8959e2150e40659ca5ce32fff36a96da0/miserables.json";

    d3.json(url, function (error, graph) {
        alert('mpe')
        if (error) throw error;

        var link = svg
        .append("g")
        .attr("class", "links")
        .selectAll("line")
        .data(graph.links)
        .enter()
        .append("line")
        .attr("stroke-width", function (d) {
            return Math.sqrt(d.value);
        });

        var node = svg
        .append("g")
        .attr("class", "nodes")
        .selectAll("g")
        .data(graph.nodes)
        .enter()
        .append("g");

        var circles = node
        .append("circle")
        .attr("r", 5)
        .attr("fill", function (d) {
            return color(d.group);
        })
        .call(
            d3
            .drag()
            .on("start", dragstarted)
            .on("drag", dragged)
            .on("end", dragended)
        );

        var lables = node
        .append("text")
        .text(function (d) {
            return d.id;
        })
        .attr("x", 6)
        .attr("y", 3);

        node.append("title").text(function (d) {
        return d.id;
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
    });

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