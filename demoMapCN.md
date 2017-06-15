**Demo# China Map** 



       use peterziv\echarts\MapCN;
       
       echo MapCN::widget([
            'responsive' => true,
            'htmlOptions' => ['style' => 'height: 400px;'],
            'title' => 'China Map Test',
    		'cityData'=>['成都'=>90,'衡水'=>60],
            'provinceData'=>['北京'=>100,'四川'=>100]
        ]);
