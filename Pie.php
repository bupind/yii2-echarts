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
class Pie extends Echarts
{
    public $backgroundColor;
    public $visualMap = [];
    public $title;
    /**
     * Pie chart data
     * @var array
     * @example ['n1'=>['value'=>11.1],'n2'=>['value'=>22.2]]
     */
    public $data = [];
    
    private function createSeriesData(){
        $data = [];
        foreach ($this->data as $key => $val) {
            $data[] = ['name'=>$key,'value'=>  $val['value']];
        }
        return [[
            "name"=>  $this->title,
            "type"=> "pie",
            "radius"=> "55%",
            "roseType"=>"angle",
            "label"=> ["normal"=> ["textStyle"=> ["color"=> "rgba(255, 0, 255, 0.3)"]]],
            "labelLine" => ["normal" => ["lineStyle" => ["color" => "rgba(255, 0, 255, 0.3)"]]],
            "itemStyle"=>["normal" => ["shadowBlur"=> 200,"shadowColor"=>"rgba(0, 0, 0, 0.5)"]],
            "data"=>  $data
        ]];
    }
    protected function initOptions(){
        $this->options = [
            "title"=> ['text'=>  $this->title,'left'=>'center','top'=>20],
            "tooltip"=>['trigger'=>'item','formatter'=>"{b} ({d}%)"],
            "visualMap"=>  $this->visualMap,
            "series"=> $this->createSeriesData()
        ];
        if (!empty($this->backgroundColor)){
            $this->options['backgroundColor'] = $this->backgroundColor;
        }
        parent::initOptions();
    }
}
