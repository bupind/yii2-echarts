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

/**
 * <p>Implement for Pie chart based on Echarts</p>
 * @see http://echarts.baidu.com/examples.html
 */
class Pie extends Echarts
{

    public $backgroundColor;
    public $visualMap = [
        'show' => false,
        'min' => 80,
        'max' => 600
    ];

    /**
     * The title of this chart
     * @var string
     */
    public $title;

    /**
     * Pie chart data
     * @var array <p>['n1'=>['value'=>11.1],'n2'=>['value'=>22.2]]</p>
     */
    public $data = [];

    /**
     * The color of pie, default is #c23531
     * @var string
     */
    public $color = '#c23531';

    /**
     * create series data from input parameters
     * @return array <p>return the series data from input parameters</p>
     */
    private function createSeriesData(){
        $data = [];
        foreach ($this->data as $key => $val) {
            $data[] = ['name'=>$key,'value'=>  $val['value']];
        }
        return [[
            'name' => $this->title,
            'type' => 'pie',
            'radius' => '60%',
            'center' => ['50%', '50%'],
            'roseType' => 'radius',
            'label' => ['normal' => ['textStyle' => ['color' => 'rgba(0, 0, 0, 0.8)']]],
            'labelLine' => ['normal' => [
                    'lineStyle' => ['color' => 'rgba(255, 0, 255, 0.3)'],
                    'smooth' => 0.2,
                    'length' => 10,
                    'length2' => 20
                ]],
            'itemStyle' => ['normal' => ['color' => $this->color, 'shadowBlur' => 60, 'shadowColor' => 'rgba(255, 255, 0, 0.3)']],
            'data' => $data
        ]];
    }

    /**
     * override initiate for options parameter
     */
    protected function initOptions(){
        $this->options = [
            'title' => ['text' => $this->title, 'left' => 'center', 'top' => 20],
            'tooltip' => ['trigger' => 'item', 'formatter' => '{b} ({d}%)'],
            'visualMap' => $this->visualMap,
            'series' => $this->createSeriesData()
        ];
        if (!empty($this->backgroundColor)){
            $this->options['backgroundColor'] = $this->backgroundColor;
        }
        parent::initOptions();
    }

}
