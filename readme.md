
## Adding visualization modules to Datawrapper

This tutorial shows how you can new visualization modules to Datawrapper.

Visualizations are provided by plugins, so you need to create a new plugin. A plugin can provide one or many visualizations at the same time.

### File structure

Plugin:

* [package.json](package.json) - plugin descriptor and basically follows the same syntax as used by npm packages
* [plugin.php](plugin.php) - plugin PHP class

Visualization:

* [bubble-chart.json](bubble-chart.json) - visualization descriptor
* static/[bubble-chart.js](static/bubble-chart.js) - JavaScript code that runs the visualization
* static/[bubble-chart.css](static/bubble-chart.css) - CSS code to support
* static/[bubble-chart.svg](static/bubble-chart.svg) - visualization icon
* static/vendor/d3.min.js - D3.js library

### Visualization descriptor

While most of the core visualizations define the descriptor in PHP (which makes it easier to translate the title and options), in this case a simple JSON file is used which is then [parsed by the PHP class](plugin.php#L6).

* [id](bubble-chart.json#L2) (bubble-chart) - unique id for the visualization
* [title](bubble-chart.json#L3) ("Bubble Chart (d3)") - the module name as displayed in the editor
* [libraries](bubble-chart.json#L4-L7) - array of third-party libraries that are used by this vis (optional)
* [axes](bubble-chart.json#L8-L18) - the axes (or dimensions) provided by the visualization
* [options](bubble-chart.json#L19-L41) - the config options displayed to the user

### Visualization JavaScript