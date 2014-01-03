
## Adding visualization modules to Datawrapper

This tutorial shows how you can new visualization modules to Datawrapper.

### The plugin

The first thing we need is a plugin which will serve as 'host' for our visualization. In general a Datawrapper plugin needs just one single file, the plugin descriptor ([package.json](package.json)):

```json
{
    "name": "d3-bubble-chart",
    "version": "1.0.0",
    "dependencies": {
        "core": "1.5.0",
        "visualization": "*"
    }
}
```

However, in this case the plugin wants to do a little more, so we need to give it a PHP class ([plugin.php](plugin.php)) that will be loaded and executed by the Datawrapper core. To make life easier we added core plugin ([DatawrapperPlugin_Visualization](https://github.com/datawrapper/datawrapper/blob/master/plugins/visualization/plugin.php)) that you can extend. Now you just need to implement getMeta() which is called to get the visualization meta blob (described in the next section).

While most of the core visualizations define the descriptor entirely in PHP (which makes it easier to translate the title and options), in this case a simple JSON file is used instead, which is then [parsed by the PHP class](plugin.php#L6).

```php
<?php

class DatawrapperPlugin_D3BubbleChart extends DatawrapperPlugin_Visualization {

    public function getMeta(){
        $path = dirname(__FILE__) . DIRECTORY_SEPARATOR;
        return json_decode(file_get_contents($path . "bubble-chart.json"), true);
    }
}
```


### File structure

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