<?php

/**
 * Line chart
 *
 * @author Peter <peter.ziv@hotmail.com>
 * @date Otc 22,2016
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 * @link https://github.com/peterziv/yii2-echarts
 * @package peterziv\echarts
 */

namespace peterziv\echarts;

/**
 * Implement for Line chart based on Echarts
 * @see http://echarts.baidu.com/examples.html
 */
class Line extends Echarts
{

    public $backgroundColor;
    public $title = 'Line Title';
    public $unit = '';
    public $axis = [];

    /**
     * series data
     * @link http://echarts.baidu.com/demo.html#line-marker
     * @var array <p>
     * [
     *      'serie1'=>[
     *          'value'=>[1,2,3],
     *          'averageLine'=>true,
     *          'maxPoint'=>true,
     *          'minPoint'=>true
     *      ],
     *      'serie2'=>['averageLine'=>true,'value'=>[3,6,9]]
     * ]
     *</p>
     * @optional <p>averageLine | maxPoint | minPoint </p>
     */
    public $data = [];

    /**
     * create series data from input parameters
     * @return array <p>return the series data from input parameters</p>
     */
    private function createSeriesData()
    {
        $series = [];
        foreach ($this->data as $key=>$val) {
            $serie = ['name' => $key, 'type' => 'line', 'data' => $val['value']];
            foreach ($val as $key => $value) {
                if (!$value) {
                    continue;
                }
                $this->setMarks($serie, $key);
            }
            $series[] = $serie;
        }
        return $series;
    }

    /**
     * set the max/min point
     * @param array $serie <p>The marks data for chart</p>
     * @param array $type <p>Only support minPoint and maxPoint now.</p>
     */
    private function setMarks(&$serie, $type)
    {
        switch ($type) {
            case 'averageLine':
                $serie['markLine'] = ['data' => [
                            ['type' => 'average', 'name' => 'Average']
                ]];
                break;
            case 'minPoint':
                $serie['markPoint']['data'][] = ['type' => 'min', 'name' => 'Min'];
                break;
            case 'maxPoint':
                $serie['markPoint']['data'][] = ['type' => 'max', 'name' => 'Max'];
                break;
        }
    }

    /**
     * override initiate for options parameter
     */
    protected function initOptions()
    {
        $this->options = [
            "title" => ['text' => $this->title, 'left' => 'center', 'top' => 20],
            "xAxis" => [
                'type' => 'category',
                'boundaryGap' => false,
                'data' => $this->axis
            ],
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
