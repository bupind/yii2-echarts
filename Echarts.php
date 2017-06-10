<?php

/**
 * Echarts class file.
 *
 * @author Peter <peter.ziv@hotmail.com>
 * @date Otc 22,2016
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */

namespace peterziv\echarts;

use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\View;

/**
 * Echarts encapsulates the {@link http://echarts.baidu.com Echarts}
 * charting library's Chart object.
 *
 * To use this widget, you can insert the following code in a view:
 * ```php
 * echo Echarts::widget([
 *                 'responsive' => true,
 *                 'htmlOptions'=>['style' => 'height: 400px;'],
 *                 'options' => [
 *                     'title' => [
 *                         'text' => '折线图堆�?'
 *                     ],
 *                     'tooltip' => [
 *                         'trigger' => 'axis'
 *                     ],
 *                     'legend' => [
 *                         'data' => ['邮件营销', '联盟广告', '视频广告', '直接访问', '搜索引擎']
 *                     ],
 *                     'grid' => [
 *                         'left' => '3%',
 *                         'right' => '4%',
 *                         'bottom' => '3%',
 *                         'containLabel' => true
 *                     ],
 *                     'toolbox' => [
 *                         'feature' => [
 *                             'saveAsImage' => []
 *                         ]
 *                     ],
 *                     'xAxis' => [
 *                         'name' => '日期',
 *                         'type' => 'category',
 *                         'boundaryGap' => false,
 *                         'data' => ['周一', '周二', '周三', '周四', '周五', '周六', '周日']
 *                     ],
 *                     'yAxis' => [
 *                         'type' => 'value'
 *                     ],
 *                     'series' => [
 *                         [
 *                             'name' => '邮件营销',
 *                             'type' => 'line',
 *                             'stack' => '总量',
 *                             'data' => [120, 132, 101, 134, 90, 230, 210]
 *                         ],
 *                         [
 *                             'name' => '联盟广告',
 *                             'type' => 'line',
 *                             'stack' => '总量',
 *                             'data' => [220, 182, 191, 234, 290, 330, 310]
 *                         ],
 *                         [
 *                             'name' => '视频广告',
 *                             'type' => 'line',
 *                             'stack' => '总量',
 *                             'data' => [150, 232, 201, 154, 190, 330, 410]
 *                         ],
 *                         [
 *                             'name' => '直接访问',
 *                             'type' => 'line',
 *                             'stack' => '总量',
 *                             'data' => [320, 332, 301, 334, 390, 330, 320]
 *                         ],
 *                         [
 *                             'name' => '搜索引擎',
 *                             'type' => 'line',
 *                             'stack' => '总量',
 *                             'data' => [820, 932, 901, 934, 1290, 1330, 1320]
 *                         ]
 *                     ]
 *                 ],
 *                 'events' => [
 *                     'click' => [
 *                         new JsExpression('function (params) {console.log(params)}'),
 *                         new JsExpression('function (params) {console.log("ok")}')
 *                     ],
 *                     'legendselectchanged' => new JsExpression('function (params) {console.log(params.selected)}')
 *                 ],
 *             ]);
 * 
 * ```
 *
 * By configuring the {@link $options} property, you may specify the options
 * that need to be passed to the Echarts JavaScript object. Please refer to
 * the demo gallery and documentation on the {@link http://echarts.baidu.com
 * Echarts website} for possible options.
 *
 * Note: You do not need to specify the <code>chart->renderTo</code> option as
 * is shown in many of the examples on the Highcharts website. This value is
 * automatically populated with the id of the widget's container element. If you
 * wish to use a different container, feel free to specify a custom value.
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
     * @var array the attached event handlers for the echarts plugin (event name => handlers)
     */
    public $events = [];
    
    public $scripts = [];
    
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
        array_unshift($this->scripts, $this->baseScript);

        $this->registerAssets();

        parent::run();
    }
    
    protected function initOptions(){
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

        // 
        $client = "echarts_{$this->id}";
        if ($this->theme) {
            $js = "var {$client} = echarts.init(document.getElementById('{$this->id}'), " . $this->quote($this->theme) . ");";
        } else {
            $js = "var {$client} = echarts.init(document.getElementById('{$this->id}'));";
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
     *
     * @param string $string
     * @return string the quoted string
     */
    private function quote($string) {
        return "'" . addcslashes($string, "'") . "'";
    }
}
