<?php
/**
 * @var \common\components\View $this
 * @var \yii\data\ActiveDataProvider $dataProvider
 */

use common\widgets\DataTables\DataColumn;
use yii\helpers\Html;

$this->title = Yii::t('app', 'Driver Compliance Tracking');
$this->params['breadcrumbs'][] = $this->title;
$dateFormat = Yii::$app->params['formats'][9];
$f = function ($model, $attribute) {
    $date = $model->driverCompliance && $model->driverCompliance->$attribute ? Yii::$app->formatter->asDate($model->driverCompliance->$attribute, Yii::$app->params['formats'][9]) : null;
    $attribute .= '_diff';
    $date_diff = $model->driverCompliance ? $model->driverCompliance->$attribute : null;
    $class = null;
    if (is_int($date_diff)) {
        if ($date_diff >= 1 && $date_diff <= 30) $class = 'driver-text-orange';
        if ($date_diff <= 0) $class = 'driver-text-red';
    }
    return '<span' . ($class ? ' class="' . $class . '"' : '') . '>' . $date . '</span>';
};
echo \common\widgets\DataTables\Grid::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        'null|title=Driver|method=getFullName',
        'type|title=COS|abbreviation|className=text-center',
        'office_id|title=Off|rel=office.office|className=text-center',
        new DataColumn([
            'title' => Yii::t('app', 'CDL'),
            'className' => 'text-center',
            'value' => function ($model) use ($f) {
                return $f($model, 'cdl_expires');
            }
        ]),
        new DataColumn([
            'title' => Yii::t('app', 'Haz'),
            'className' => 'text-center',
            'value' => function ($model) {
                return $model->driverCompliance->haz_mat ? 'Y' : 'N';
            }
        ]),
        new DataColumn([
            'title' => Yii::t('app', 'Haz Exp'),
            'className' => 'text-center',
            'value' => function ($model) use ($f) {
                return $f($model, 'haz_mat_expires');
            }
        ]),
        new DataColumn([
            'title' => Yii::t('app', 'WA'),
            'className' => 'text-center',
            'value' => function ($model) use ($f) {
                return $f($model, 'work_auth_expires');
            }
        ]),
        new DataColumn([
            'title' => Yii::t('app', 'TWIC'),
            'className' => 'text-center',
            'value' => function ($model) use ($f) {
                return $f($model, 'twic_exp');
            }
        ]),
        new DataColumn([
            'title' => Yii::t('app', 'Last DS'),
            'className' => 'text-center',
            'value' => function ($model) use ($f) {
                return $f($model, 'last_drug_test');
            }
        ]),
        new DataColumn([
            'title' => Yii::t('app', 'Last AT'),
            'className' => 'text-center',
            'value' => function ($model) use ($f) {
                return $f($model, 'last_alcohol_test');
            }
        ]),
        new DataColumn([
            'title' => Yii::t('app', 'Phys'),
            'className' => 'text-center',
            'value' => function ($model) use ($f) {
                return $f($model, 'next_dot_physical');
            }
        ]),
        new DataColumn([
            'title' => Yii::t('app', 'FFD'),
            'className' => 'text-center',
            'value' => function ($model) use ($f) {
                return $f($model, 'next_ffd_evaluation');
            }
        ]),
        new DataColumn([
            'title' => Yii::t('app', 'H2S'),
            'className' => 'text-center',
            'value' => function ($model) use ($f) {
                return $f($model, 'next_h2s_certification');
            }
        ]),
        new DataColumn([
            'title' => Yii::t('app', 'Vio'),
            'className' => 'text-center',
            'value' => function ($model) use ($f) {
                return $f($model, 'next_vio_review');
            }
        ]),
        new DataColumn([
            'title' => Yii::t('app', 'MVR'),
            'className' => 'text-center',
            'value' => function ($model) use ($f) {
                return $f($model, 'next_mvr_review');
            }
        ]),
        'notes',
        new \common\widgets\DataTables\ActionColumn([
            'html' => function (\common\models\Driver $model) {
                return Html::a('<i class="fas fa-sm fa-vial"></i>', ['update', 'id' => $model->id], ['class' => 'px-1', 'data-tooltip' => 'tooltip', 'data-placement' => 'top', 'title' => Yii::t('app', 'Compliance')]);
            }
        ])
    ]
]);
