
(function () {

    dw.visualization.register('bubble-chart', {

        render: function(el) {

            // build data structure from dataset
            var axes = this.axes(true),
                data = { children: [] };
            this.dataset.eachRow(function(i) {
                data.children.push({
                    label: axes.label.val(i),
                    value: axes.size.val(i),
                    color: axes.color.val(i)
                });
            });

            // create color scale
            var color = axes.color.type() == 'number' // if the color axis is numerical..
                    // use a linear scale to interpolate colors
                    ? d3.scale.linear()
                      .domain(axes.color.range())
                      .range(this.theme().colors.gradients[0])
                    // otherwise use ordinal scale
                    : d3.scale.ordinal()
                      .range(this.theme().colors.categories[0]);

            // get chart size
            var size = this.size(),  // returns array [w, h]
                r = Math.min(size[0], size[1]);  // total radius must fit in chart

            // the usual D3 code below
            var format = d3.format(",d");

            var bubble = d3.layout.pack()
                .sort(null)
                .size([r, r]);

            var vis = d3.select(el.get(0)).append("svg:svg")
                .attr("width", r)
                .attr("height", r)
                .style("margin-left", (size[0] - r) / 2)
                .attr("class", "bubble");

            var node = vis.selectAll("g.node")
                .data(bubble.nodes(data).filter(function(d) { return !d.children; }))
            .enter().append("svg:g")
                .attr("class", "node")
                .attr("transform", function(d) {
                    return "translate(" + d.x + "," + d.y + ")";
                });

            node.append("svg:title")
                .text(function(d) {
                    return d.label + ": " + format(d.value);
                });

            node.append("svg:circle")
                .attr("r", function(d) { return d.r; })
                .style("fill", function(d) { return color(d.color); });

            node.append("svg:text")
                .attr("text-anchor", "middle")
                .attr("dy", ".3em")
                .attr("class", function(d) {
                    return d3.lab(color(d.color)).l < 80 ? "inverted" : "";
                })
                .text(function(d) {
                    return d.label.substring(0, d.r / 5);
                });

        }

    });

}).call(this);