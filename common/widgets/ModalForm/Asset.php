<?php

namespace common\widgets\ModalForm;

use yii\web\AssetBundle;

class Asset extends AssetBundle
{
    public $sourcePath = '@common/widgets/ModalForm/assets';
    public $js = [
        'scripts.js'
    ];
    public $depends = [
        'yii\web\JqueryAsset'
    ];
}
