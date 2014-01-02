<?php

class DatawrapperPlugin_D3BubbleChart extends DatawrapperPlugin_Visualization {

    public function getMeta(){
        return json_decode(file_get_contents(dirname(__FILE__) . DIRECTORY_SEPARATOR . "bubble-chart.json"), true);
    }

}
