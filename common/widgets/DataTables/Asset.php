<?php

namespace common\widgets\DataTables;

use yii\web\AssetBundle;

class Asset extends AssetBundle
{
    public $sourcePath = '@common/widgets/DataTables/assets';
    public $js = [
        'scripts.js'
    ];
    public $depends = [
        'yii\web\JqueryAsset'
    ];
}
