<?php

/**
 * Echarts class file.
 *
 * @author Peter <peter.ziv@hotmail.com>
 * @date Otc 22,2016
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */

namespace peterziv\echarts;

/**
 * @see Highcharts
 */
class Line extends Echarts {

    public $backgroundColor;
    public $title = 'TITLE';
    public $unit = '';
    public $axis = [];
    /**
     * series data
     * @var array 
     * @example {@link http://echarts.baidu.com/demo.html#line-marker} as follow
     * @example ['serie1'=>['value'=>[1,2,3],'averageLine'=>true,'maxPoint'=>true, 'minPoint'=>true],'serie2'=>['averageLine'=>true,'value'=>[3,6,9]]]
     * @optional: averageLine maxPoint minPoint
     * 
     */
    public $data = [];

    private function createSeriesData() {
        $series = [];
        foreach ($this->data as $key=>$val) {
            $serie = ['name'=>$key,'type'=> 'line','data'=> $val['value']];
            if(isset($val['averageLine']) && true === $val['averageLine']){
                $serie['markLine'] = ['data'=>[['type'=>'average','name'=>'Average']]];
            }
            $marks = [];
            if (isset($val['maxPoint']) && true === $val['maxPoint']) {
                $marks[] = ['type' => 'max', 'name' => 'Max'];
            }
            if (isset($val['minPoint']) && true === $val['minPoint']) {
                $marks[] = ['type' => 'min', 'name' => 'Min'];
            }
            if (!empty($marks))
                $serie['markPoint'] = ['data' => $marks];
            $series[] = $serie;
        }
        return $series;
    }

 protected function initOptions() {
        $this->options = [
            "title" => ['text' => $this->title, 'left' => 'center', 'top' => 20],
            "xAxis" => ['type' =>  'category','boundaryGap'=>false, 'data'=> $this->axis ],
            "yAxis" => [ ['name' => $this->unit, 'type'=>'value']],
            "legend" => ['data' =>  array_keys($this->data) ,'left'=>'left'],
            "tooltip" => ['trigger' => 'item', 'formatter' => "{a}<br/>{b}: {c}"],
            "series" => $this->createSeriesData()
        ];
        if (!empty($this->backgroundColor)) {
            $this->options['backgroundColor'] = $this->backgroundColor;
        }
        parent::initOptions();
    }

}
