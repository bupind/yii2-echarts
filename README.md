# yii2-echarts
This is the Yii2 widgets for echarts.
![Echarts](http://echarts.baidu.com/images/logo.png)

You can get detail of echarts  from [echarts official website](http://echarts.baidu.com/) :)

## 安装 (Installation):

```
$ composer require "peterziv/yii2-echarts"
```


## 示例 (Demo):

Pie Chart
```php
$pieData = ['n1' => ['value' => 11.1], 'n2' => ['value' => 22.2]];
echo Pie::widget([
    'title'=>'Pie Chart Test',
    'responsive' => true,
    'htmlOptions' => ['style' => 'height: 300px;'],
    'visualMap' => [
    	"show" => false,
    	"min" => 80,
    	"max" => 600,
    	"inRange" => ["colorLightness" => [0, 1]]
    ],
	'data' => $pieData
]);
```

Line Chart
```php
$lineData = ['serie1' => ['value' => [1, 2, 3], 'averageLine' => true, 'maxPoint' => true, 'minPoint' => true], 'serie2' => ['averageLine' => true, 'value' => [3, 6, 9]]];
echo Line::widget([
	'responsive' => true,
	'htmlOptions' => ['style' => 'height: 300px;'],
    'title' => 'Line Chart Test',
    'unit'=>'度',
    'axis'=>['小','中','大'],
    'data'=>$lineData
]);
```

Zero data support

```php
$zero =[];
echo Line::widget([
  'htmlOptions' => ['style' => 'height: 300px;'],
  'responsive' => true,
  'title' => 'No Data Test',
  'unit' => '度',
  'axis' => ['小', '中', '大'],
  'data' => $zero
]);
```

Also Support json and custom
```php
$theOptions =  '{
	"title": {
		"text": "Json data Support"
	},
	"legend": {
		"data": ["bar1", "bar2"],
		"align": "left"
	},
	"toolbox": {
		"feature": {
			"magicType": {
				"type": ["stack", "tiled"]
			}
		}
	},
    "yAxis":[{"name":"SCORE","type":"value"}],
	"xAxis": {
		"data": ["测试1", "测试2", "测试3", "测试4"],
		"silent": false,
		"splitLine": {
			"show": false
		}
	},
	"series": [{
		"name": "bar1",
		"type": "bar",
		"data": [1, 2, 5, 8]
	}, {
		"name": "bar2",
		"type": "bar",
		"data": [9, 6, 5, 8]
	}]
}';
echo Echarts::widget([
  'options'=>$theOptions,
  'htmlOptions' => ['style' => 'height: 300px;'],
]);
```

