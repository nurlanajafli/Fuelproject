<?php


namespace backend\widgets;


use yii\web\AssetBundle;

class DatatableAssets extends AssetBundle
{
    public $depends = ['yii\web\YiiAsset', 'yii\bootstrap\BootstrapAsset'];
    public $js = ['/js/jquery.dataTables.min.js', '/js/dataTables.bootstrap.min.js'];
    public $css = ['/css/dataTables.bootstrap.min.css'];
}