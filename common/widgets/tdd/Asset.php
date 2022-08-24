<?php

namespace common\widgets\tdd;

use yii\web\AssetBundle;

class Asset extends AssetBundle
{
    public $sourcePath = '@common/widgets/tdd/assets';
    public $js = [
        'scripts.js'
    ];
    public $depends = [
        'yii\web\JqueryAsset'
    ];
}
