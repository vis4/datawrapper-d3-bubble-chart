
## Adding visualization modules to Datawrapper

This tutorial shows how to add new visualization modules to Datawrapper. In this example we are going to add a nice [D3.js bubble chart](http://bl.ocks.org/mbostock/4063269) so Datawrapper users can create them without writing a single line of code.

![bubble chart](https://gist.github.com/mbostock/4063269/raw/5144eafeac9e298962133e9e31de45da21714108/thumbnail.png)

Additionally to reading this tutorial you can also [check the commit history of this repository](https://github.com/datawrapper/tutorial-visualization/commits/master) to follow the individual steps.

### Creating the host plugin

In general, to extend Datawrapper with new features you need to [create a plugin](https://github.com/datawrapper/datawrapper/wiki/Extending-Datawrapper). Plugins can do a lot of things, and adding new visualizations is just one of them.

The plugins are stored inside the [``plugins``](https://github.com/datawrapper/datawrapper/tree/master/plugins) folder, so we create a new folder ``d3-bubble-chart`` for our plugin.

Now you need to [create the package.json file](https://github.com/datawrapper/tutorial-visualization/commit/5cac9a2ccdafcd334f51aa73c492ca7dc9d7b7c6) which provides some meta information about the plugin itself (very similar to NPM package.json). The attributes ``name`` and ``version`` are required. The ``name`` must be the same as the plugin folder name.

```json
{
    "name": "d3-bubble-chart",
    "version": "1.0.0"
}
```

As our plugin wants to provide a new visualization we need to [create the plugin class plugin.php](https://github.com/datawrapper/tutorial-visualization/commit/22c5d5b494a37d38efb0c32accc12c61cb134c12). The plugin class will be loaded by Datawrapper and its ``init()`` function is invoked on every request.

```php
<?php

class DatawrapperPlugin_D3BubbleChart extends DatawrapperPlugin {

    public function init(){
        // do some stuff here
    }
}
```

By now the plugin is already ready for installation. To do so you open a command line and run the following inside the Datawrapper root folder:

```bash
$ php scripts/plugin.php install d3-bubble-chart
Installed plugin d3-bubble-chart.
```

### Register the visualization

To register the visualization we will execute a special core method ``DatawrapperVisualization::register``. It takes two arguments: the plugin that provides the visualization (``$this``) and an array with the visualization meta data (``$visMeta``).

[We start easy](https://github.com/datawrapper/tutorial-visualization/commit/2a75fdb29ce4466ffab6043695721be675fcc44b) with just some basic meta information: the visualization ``id`` and the ``title`` that is displayed in the chart editor.

```php
<?php

class DatawrapperPlugin_D3BubbleChart extends DatawrapperPlugin {

    public function init(){
        $visMeta = array(
            "id" => "bubble-chart",
            "title" => "Bubble Chart (d3)"
        );
        DatawrapperVisualization::register($this, $visMeta);
    }
}
```

### Define the visual axes

At this point it's time to introduce the concept of axes we are using in Datawrapper. Axes (aka dimensions) are the visual properties that are later mapped to columns of the uploaded dataset. For example, an simple scatter plot would provide two axes for the x and y position of each dot, each of which would accept numerical values.

In case of our bubble chart we at least need two axes for the bubble size and a label to be displayed on each bubble. The label axis accepts text and date columns while the size accepts only numerical columns.

```php
<?php

class DatawrapperPlugin_D3BubbleChart extends DatawrapperPlugin {

    public function init(){
        $visMeta = array(
            "id" => "bubble-chart",
            "title" => "Bubble Chart (d3)",
            "axes" => array(
                "label" => array(
                    "accepts" => array("text", "date")
                ),
                "size" => array(
                    "accepts" => array("number")
                )
            )
        );
        DatawrapperVisualization::register($this, $visMeta);
    }
}
```

[Once the axes are defined](https://github.com/datawrapper/tutorial-visualization/commit/9f3797cfd019370132f2c81164d652228b033bd6) Datawrapper will automatically assign data columns to them when a new chart is created. The first text or date column is assigned to the ``label`` axis, and the first number column is assigned to the ``size`` column.

### Prepare the visualization JavaScript

At first we need to create the JavaScript file that is loaded with the chart. Like any plugin file that we want to be publicly accessible, we must be locate it in a sub-folder named ``static/``.

It is important to name the file exactly after the visualization id you defined in the ``$visMeta`` array above, so in this case we would name it [bubble-chart.js](https://github.com/datawrapper/tutorial-visualization/tree/deea47fa54e93dac684506e48924962eb5986481/d3-bubble-chart/static).

In the [base skeleton for the file](https://github.com/datawrapper/tutorial-visualization/blob/deea47fa54e93dac684506e48924962eb5986481/d3-bubble-chart/static/bubble-chart.js) we simply call the framework function ``dw.visualization.register`` to register it's code. As first argument we pass the visualization id and last second argument is an object with a function ``render()``.

```javascript
dw.visualization.register('bubble-chart', {

    render: function($element, dataset, axes, theme) {
        // render the visualization inside $element
    }

});
```

And this ``render`` function is where all our code will go into.

### Code the visualization!

Now it is the time where the actual fun starts, as we are switching to JavaScript to code our visualization. Let's begin with collecting and preparing the data.

The bubble chart code (that we [adapted from this example](https://gist.github.com/mbostock/4063269)) expects the data in a structure like this:

```json
{
    "children": [
        { "label": "Bubble 1", "value": 123 },
        { "label": "Bubble 2", "value": 234 }
    ]
}
```

To access the dataset.

```javascript
render: function($element, dataset, axes, theme) {
    var data = { children: [] };
    dataset.eachRow(function(i) {
        data.children.push({
            label: axes.label.val(i),
            value: axes.size.val(i),
            color: axes.color.val(i)
        });
    });
}
```

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



### Don't stop here

But obviously what we achieved so far is nice but it's not enough to stop here. A visualization is a poor visualization if there is no way to read the actual values. So we could improve the bubble chart by adding a radius legend. If the radius is big enough we could (and should) also display the values directly inside the bubbles. And if we're using a color scale it would be help a lot to include a color legend, too. Finally we could make the visualization more useful by allowing the user to customize the color scale. Therefor we could re-use the gradient-selector plugin used by the map visualization.. And why not add support for IE7 and IE8 by using raphael.js for the rendering?