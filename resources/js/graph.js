console.log('graph.js loaded')

import * as d3 from "d3";

let data = false;
if (typeof(graph_data) != 'undefined') {
  data = graph_data;
}

if (data) {
  const drag = simulation => {
    
    function dragstarted(event) {
      if (!event.active) simulation.alphaTarget(0.3).restart();
      event.subject.fx = event.subject.x;
      event.subject.fy = event.subject.y;
    }
    
    function dragged(event) {
      event.subject.fx = event.x;
      event.subject.fy = event.y;
    }
    
    function dragended(event) {
      if (!event.active) simulation.alphaTarget(0);
      event.subject.fx = null;
      event.subject.fy = null;
    }
    
    return d3.drag()
        .on("start", dragstarted)
        .on("drag", dragged)
        .on("end", dragended);
  }

  const color = () => {
    const scale = d3.scaleOrdinal(d3.schemeCategory10);
    return d => scale(d.group);
  }

  let width = screen.width * 0.65;
  let height = screen.height * 0.68;

  if (screen.width <= 768) {
    width = screen.width * 0.95;
  }

  const links = data.links.map(d => Object.create(d));
  const nodes = data.nodes.map(d => Object.create(d));

  const simulation = d3.forceSimulation(nodes)
      .force("link", d3.forceLink(links).id(d => d.id))
      .force("charge", d3.forceManyBody())
      .force("center", d3.forceCenter(width / 2, height / 2));

  const svg = d3.select(".graph-container > svg")
    .attr('width', width)
    .attr('height', height);

    // .attr("preserveAspectRatio", "xMinYMin meet")
    // .attr("viewBox", "0 0 800 400")
    // .classed("svg-content", true);

  const link = svg.append("g")
      .attr("stroke", '#181818')
      .attr("stroke-opacity", 0.6)
      .selectAll("line")
      .data(links)
      .join("line")
      .attr("stroke-width", d => Math.sqrt(d.value));

  const node = svg.append("g")
      .attr("stroke", "#fff")
      .attr("stroke-width", 1.5)
      .selectAll("circle")
      .data(nodes)
      .join("circle")
      .attr("r", 5)
      .attr("fill", '#2C7A7B')
      .call(drag(simulation));

  node.append("title")
      .text(d => d.screen_name);

  simulation.on("tick", () => {
      link
          .attr("x1", d => d.source.x)
          .attr("y1", d => d.source.y)
          .attr("x2", d => d.target.x)
          .attr("y2", d => d.target.y);

      node
          .attr("cx", d => d.x)
          .attr("cy", d => d.y);
  });
}
