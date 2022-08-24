<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'https://fonts.googleapis.com/css2?family=Source+Sans+Pro&display=swap',
        'css/site.css',
    ];
    public $depends = [
        'backend\assets\MarkupAsset',
        'common\widgets\DataTables\Asset',
        'common\widgets\tdd\Asset',
        'common\widgets\ModalForm\Asset',
    ];
}
