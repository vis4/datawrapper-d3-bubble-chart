<?php

class DatawrapperPlugin_D3BubbleChart extends DatawrapperPlugin {

    public function init() {

        $visMeta = array(
            /*
             * The unique visualization id (not to be confused with the plugin id)
             */
            "id" => "bubble-chart",

            /*
             * The title displayed in the editor UI. Wrap in __() to make it
             * localizable.
             */
            "title" => "Bubble Chart (d3)",

            /*
             * Optional: libraries that are used by the visualization.
             */
            "libraries" => array(array(
                "local" => "vendor/d3.min.js",
                "cdn" => "//cdnjs.cloudflare.com/ajax/libs/d3/3.3.11/d3.min.js"
            )),

            /*
             * The axes (or dimensions) provided by the visualization. The bubble
             * chart provides three axes for the bubble radius (size), fill (color)
             * and label.
             */
            "axes" => array(
                "label" => array(
                    "accepts" => array("text", "date")
                ),
                "size" => array(
                    "accepts" => array("number")
                ),
                "color" => array(
                    "accepts" => array("number", "text"),
                    "optional" => true
                )
            ),

            /*
             * The config options that are displayed to the user in the right sidebar
             * in the chart editor. In this case three options are defined for assigning
             * the columns to each of the axes.
             */
            "options" => array(
                "select-label" => array(
                    "type" => "select-axis-column",
                    "axes" => array(array(
                        "id" => "label",
                        "label" => "Label"
                    ))
                ),
                "select-size" => array(
                    "type" => "select-axis-column",
                    "axes" => array(array(
                        "id" => "size",
                        "label" => "Size"
                    ))
                ),
                "select-color" => array(
                    "type" => "select-axis-column",
                    "axes" => array(array(
                        "id" => "color",
                        "label" => "Color"
                    ))
                )
            )
        );

        DatawrapperVisualization::register($this, $visMeta);
    }

}
