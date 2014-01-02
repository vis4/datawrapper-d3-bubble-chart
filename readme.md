
# How to add visualization modules to Datawrapper

This tutorial shows how you can new visualization modules to Datawrapper.

Visualizations are provided by plugins, so you need to create a new plugin. A plugin can provide one or many visualizations at the same time.

## File structure

Plugin:

* [package.json](package.json) - plugin descriptor and basically follows the same syntax as used by npm packages
* [plugin.php](plugin.php) - plugin PHP class

Visualization:

* [bubble-chart.json](bubble-chart.json) - the visualization descriptor
* static/[bubble-chart.js](static/bubble-chart.js) - JavaScript code that runs the visualization
* static/[bubble-chart.css](static/bubble-chart.css) - CSS code to support
* static/[bubble-chart.svg](static/bubble-chart.svg) - visualization icon
* static/vendor/d3.min.js - D3.js library
