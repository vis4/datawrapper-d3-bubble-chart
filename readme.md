
## Adding visualization modules to Datawrapper

This tutorial shows how to add new visualization modules to Datawrapper. In this example we are going to add a nice [D3.js bubble chart](http://bl.ocks.org/mbostock/4063269) so Datawrapper users can create them without writing a single line of code.

![bubble chart](https://gist.github.com/mbostock/4063269/raw/5144eafeac9e298962133e9e31de45da21714108/thumbnail.png)

### Creating the host plugin

In general, to extend Datawrapper with new features you need to [create a plugin](https://github.com/datawrapper/datawrapper/wiki/Extending-Datawrapper). Plugins can do a lot of things, and adding new visualizations is just one of them.

The plugins are stored inside the [``plugins``](https://github.com/datawrapper/datawrapper/tree/master/plugins) folder, so we create a new folder ``d3-bubble-chart`` for our plugin.

Now you need to create the [package.json](package.json) file which provides some meta information about the plugin itself (very similar to NPM package.json). The attributes ``name`` and ``version`` are required. The ``name`` must be the same as the plugin folder name.

```json
{
    "name": "d3-bubble-chart",
    "version": "1.0.0"
}
```

As our plugin wants to provide a new visualization we need to create the plugin class [plugin.php](plugin.php). The plugin class will be loaded by Datawrapper and its ``init()`` function is invoked. 

```php
<?php

class DatawrapperPlugin_D3BubbleChart extends DatawrapperPlugin {

    public function init(){
        // do some stuff here
    }
}
```

Now the plugin is ready for installation. To do so you open a command line and run the following inside the Datawrapper root folder:

```bash
$ php scripts/plugin.php install d3-bubble-chart
Installed plugin d3-bubble-chart.
```

### Register a new visualization

Plugin:

*  - plugin descriptor and basically follows the same syntax as used by npm packages
*  - plugin PHP class

Visualization:

* [bubble-chart.json](bubble-chart.json) - visualization descriptor
* static/[bubble-chart.js](static/bubble-chart.js) - JavaScript code that runs the visualization
* static/[bubble-chart.css](static/bubble-chart.css) - CSS code to support
* static/[bubble-chart.svg](static/bubble-chart.svg) - visualization icon
* static/vendor/d3.min.js - D3.js library

### Visualization descriptor



* [id](bubble-chart.json#L2) (bubble-chart) - unique id for the visualization
* [title](bubble-chart.json#L3) ("Bubble Chart (d3)") - the module name as displayed in the editor
* [libraries](bubble-chart.json#L4-L7) - array of third-party libraries that are used by this vis (optional)
* [axes](bubble-chart.json#L8-L18) - the axes (or dimensions) provided by the visualization. The bubble chart provides three axes for the bubble radius (size), fill (color) and label.
* [options](bubble-chart.json#L19-L41) - the config options displayed to the user. In this case three options are defined for assigning the columns to each of the axes.

### Visualization JavaScript

The JavaScript registers the new visualization to the JS core. The registered object must at least implement the ``render()`` function.

```javascript
dw.visualization.register('bubble-chart', {

    render: function($elelement) {
        // render the visualization inside $element
    }

});
```

### Don't stop here

But obviously what we achieved so far is nice but it's not enough to stop here. A visualization is a poor visualization if there is no way to read the actual values. So we could improve the bubble chart by adding a radius legend. If the radius is big enough we could (and should) also display the values directly inside the bubbles. And if we're using a color scale it would be help a lot to include a color legend, too. Finally we could make the visualization more useful by allowing the user to customize the color scale. Therefor we could re-use the gradient-selector plugin used by the map visualization.. And why not add support for IE7 and IE8 by using raphael.js for the rendering?