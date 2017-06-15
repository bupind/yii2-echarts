<?php

/**
 * The Assets for Echarts.
 *
 * @author Peter <peter.ziv@hotmail.com>
 * @date Otc 22,2016
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 * @link https://github.com/peterziv/yii2-echarts
 * @package peterziv\echarts
 */

namespace peterziv\echarts;

use yii\web\AssetBundle;

class EchartsAsset extends AssetBundle
{

    public $sourcePath = __DIR__ . DIRECTORY_SEPARATOR . 'assets';
    public $js = [
        'echarts.min.js',
        'china.js'
    ];

}
