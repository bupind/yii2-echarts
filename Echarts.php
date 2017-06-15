<?php

/**
 * Echarts.
 *
 * @author Peter <peter.ziv@hotmail.com>
 * @date Otc 22,2016
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 * @link https://github.com/peterziv/yii2-echarts
 * @package peterziv\echarts
 */

namespace peterziv\echarts;

use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\View;

/**
 * Echarts encapsulates the {@link http://echarts.baidu.com Echarts} charting library's Chart object.<p>
 * By configuring the {@link $options} property, you may specify the options
 * that need to be passed to the Echarts JavaScript object. Please refer to
 * the demo gallery and documentation on the {@link http://echarts.baidu.com
 * Echarts website} for possible options.</p>
 * @see http://echarts.baidu.com/examples.html
 */
class Echarts extends Widget
{

    protected $constr = 'Chart';
    protected $baseScript = 'echarts.common.min';
    public $options = [];
    public $htmlOptions = [];
    /**
     * @var string the theme name to be used for styling the chart.
     */
    public $theme;

    /**
     * @var array <p>
     * the attached event handlers for the echarts plugin (event name => handlers)
     * </p>
     */
    public $events = [];

    /**
     *
     * @var string <p>additional js script before the chart drawing.</p>
     */
    public $addtionalJsBefore = '';

//    public $scripts = [];

    /**
     * @var boolean whether resize the chart when the container size is changed.
     */
    public $responsive = false;

    /**
     * Renders the widget.
     */
    public function run()
    {
        // determine the ID of the container element
        if (isset($this->htmlOptions['id'])) {
            $this->id = $this->htmlOptions['id'];
        } else {
            $this->id = $this->htmlOptions['id'] = $this->getId();
        }

        // render the container element
        echo Html::tag('div', '', $this->htmlOptions);

        $this->initOptions();

        // merge options with default values
        $defaultOptions = ['chart' => ['createdby' => 'peter.ziv']];
        $this->options = ArrayHelper::merge($defaultOptions, $this->options);
//        array_unshift($this->scripts, $this->baseScript);

        $this->registerAssets();

        parent::run();
    }

    /**
     * override initiate for options parameter
     */
    protected function initOptions()
    {
        // check if options parameter is a json string
        if (is_string($this->options)) {
            $this->options = Json::decode($this->options);
        }
    }

    /**
     * Registers required assets and the executing code block with the view
     */
    protected function registerAssets()
    {
        // register the necessary script files
        EchartsAsset::register($this->view);

        //get the element in the page
        $js = $this->addtionalJsBefore;
        $client = "echarts_{$this->id}";
        if ($this->theme) {
            $js .= "var {$client} = echarts.init(document.getElementById('{$this->id}'), " . $this->quote($this->theme) . ");";
        } else {
            $js .= "var {$client} = echarts.init(document.getElementById('{$this->id}'));";
        }
        $option = is_array($this->options) ? Json::encode($this->options) : $this->options;
        $js .= "{$client}.setOption({$option});";
        if ($this->responsive) {
            $js .= "$(window).resize(function () {{$client}.resize()});";
        }
        foreach ($this->events as $name => $handlers) {
            $handlers = (array) $handlers;
            foreach ($handlers as $handler) {
                $js .= "{$client}.on(" . $this->quote($name) . ", $handler);";
            }
        }

        $key = __CLASS__ . '#' . $this->id;
        $this->view->registerJs($js, View::POS_READY, $key);
    }

    /**
     * Quotes a string for use in JavaScript.
     * @param string $str <p>The string to be escaped.</p>
     * @return string <p>The quoted string.</p>
     */
    private function quote($str)
    {
        return "'" . addcslashes($str, "'") . "'";
    }
}
