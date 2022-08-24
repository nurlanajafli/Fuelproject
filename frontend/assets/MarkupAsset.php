<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class MarkupAsset extends AssetBundle
{
    public $sourcePath = '@markup/frontend/dist';
    public $css = [
        'https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i',
        'css/sb-admin-2.css',
        'css/fontawesome-all.css',
        'css/main.css',
    ];
    public $js = [
        'js/jquery.dataTables.js',
        'js/dataTables.bootstrap4.js',
        'js/vendor.js',
        'js/scripts.js',
        'js/ext.js',

        'js/app.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
        'yii\bootstrap4\BootstrapPluginAsset'
    ];
}
