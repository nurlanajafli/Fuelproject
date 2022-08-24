<?php


namespace frontend\controllers;


use common\enums\Permission;
use common\models\Trailer;
use common\models\Truck;
use dmstr\bootstrap\Tabs;
use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;

class AssetController extends base\BaseController
{
    protected function accessRules()
    {
        return [
            [
                'allow' => true,
                'actions' => ['index'],
                'permissions' => [Permission::ASSET_MANAGER]
            ]
        ];
    }
    public function actionIndex()
    {
        $trucksCount = Truck::find()->count();
        $trailersCount = Trailer::find()->count();
        $truckDataProvider = new ActiveDataProvider(['query' => Truck::find(), 'pagination' => false]);
        $trailerDataProvider = new ActiveDataProvider(['query' => Trailer::find(), 'pagination' => false]);

        Tabs::clearLocalStorage();

        Url::remember();
        Yii::$app->session['__crudReturnUrl'] = null;

        return $this->render('index', [
            'truckDataProvider' => $truckDataProvider,
            'trailerDataProvider' => $trailerDataProvider,
            'trucksCount' => $trucksCount,
            'trailersCount' => $trailersCount,
        ]);
    }
}