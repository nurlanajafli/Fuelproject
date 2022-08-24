<?php

namespace backend\assets;

use Yii;
use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class MarkupAsset extends AssetBundle
{
    public $sourcePath = '@markup/backend/dist';
    public $css = [
        'css/main.css',
    ];
    public $js = [
        'js/jquery.dataTables.js',
        'js/dataTables.bootstrap.js',
        'js/vendor.js',
        'js/scripts.js',
        'js/ext.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
