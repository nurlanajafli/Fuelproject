<?php

namespace frontend\assets;

use yii\web\AssetBundle;
use Yii;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
//        'css/style.css'
    ];
    public $depends = [
        'yii\widgets\ActiveFormAsset',
        'common\widgets\tdd\Asset',
        'common\widgets\DataTables\Asset',
        'common\widgets\ModalForm\Asset',
        'frontend\assets\MarkupAsset',
        'yii\widgets\PjaxAsset'
    ];

    public static function getAssetUrl($asset)
    {
        $assetManager = Yii::$app->getView()->getAssetManager();
        $bundle = $assetManager->getBundle('frontend\assets\MarkupAsset');
        return $assetManager->getAssetUrl($bundle, $asset);
    }
}
