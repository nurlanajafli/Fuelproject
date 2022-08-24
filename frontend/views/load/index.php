<?php

/**
 * @var yii\data\ActiveDataProvider $dataProvider1
 * @var yii\data\ActiveDataProvider $dataProvider2
 */

use common\enums\LoadStatus;
use common\models\Load;
use common\widgets\DataTables\ActionColumn;
use common\widgets\DataTables\DataColumn;
use common\widgets\DataTables\Grid;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = Yii::t('app', 'Loads');
$this->params['breadcrumbs'][] = $this->title;

echo Grid::widget([
    'id' => 'available-loads',
    'dataProvider' => $dataProvider1,
    'columns' => [
        'id|title=Load number',
        'id|title=Pick Up date|rel=loadStops[0].available_from|date',
        'id|title=Pick Up time|rel=loadStops[0].time_from|time',
        new DataColumn([
            'title' => Yii::t('app', 'Delivery date'),
            'value' => function (Load $model) {
                return $model->loadStops ? Yii::$app->formatter->asDate($model->loadStops[count($model->loadStops) - 1]->available_from) : '';
            }
        ]),
        new DataColumn([
            'title' => Yii::t('app', 'Delivery time'),
            'value' => function (Load $model) {
                return $model->loadStops ? Yii::$app->formatter->asTime($model->loadStops[count($model->loadStops) - 1]->time_from) : '';
            }
        ]),
        new DataColumn([
            'title' => Yii::t('app', 'Pick up city'),
            'value' => function (Load $model) {
                return $model->loadStops ? $model->loadStops[0]->getCity() : '';
            }
        ]),
        new DataColumn([
            'title' => Yii::t('app', 'Pick up state'),
            'value' => function (Load $model) {
                return $model->loadStops ? $model->loadStops[0]->getStateCode() : '';
            }
        ]),
        new DataColumn([
            'title' => Yii::t('app', 'Delivery city'),
            'value' => function (Load $model) {
                return $model->loadStops ? $model->loadStops[count($model->loadStops) - 1]->getCity() : '';
            }
        ]),
        new DataColumn([
            'title' => Yii::t('app', 'Delivery state'),
            'value' => function (Load $model) {
                return $model->loadStops ? $model->loadStops[count($model->loadStops) - 1]->getStateCode() : '';
            }
        ]),
        'id|title=Truck unit|rel=dispatchAssignment.unit_id',
        'id|title=Driver name|rel=dispatchAssignment.driver.getFullName()',
        new ActionColumn([
            'html' => function (Load $model) {
                return Html::a('<i class="fas fa-edit"></i>', ['edit', 'id' => $model->id], ['class' => 'px-1', 'data-tooltip' => 'tooltip', 'data-placement' => 'top', 'title' => Yii::t('app', 'Edit')])
                    . Html::a('<i class="fas fa-trash"></i>', ['delete', 'id' => $model->id], ['class' => 'px-1', 'data-method' => 'post', 'data-confirm' => Yii::t('app', 'Are you sure to delete this item?'), 'data-tooltip' => 'tooltip', 'data-placement' => 'top', 'title' => Yii::t('app', 'Delete')]);
            }
        ]),
        new DataColumn([
            'visible' => false,
            'value' => function ($model) {
                switch (true) {
                    case $model->status == LoadStatus::DROPPED:
                        return 'load-yellow';
                    case $model->status == LoadStatus::PENDING:
                        return 'load-gray';
                    case $model->status == LoadStatus::POSSIBLE:
                        return 'load-light-gray';
                    case $model->status == LoadStatus::RESERVED:
                        return 'load-blue';
                    case $model->loadStops && $model->loadStops[0]->minutesLeftTillNow <= 0:
                        return 'load-red';
                    case $model->loadStops && $model->loadStops[0]->minutesLeftTillNow <= 60:
                        return 'load-dark-orange';
                    case $model->loadStops && $model->loadStops[0]->minutesLeftTillNow <= 120:
                        return 'load-medium-orange';
                }
                return null;
            }
        ]),

    ],
    'toolbarHtml' => '<a class="btn btn-sum btn-secondary" href="' . Url::toRoute('edit') . '"><i class="fas fa fa-plus"></i> ' . Yii::t('app', 'New') . '</a>',
    'toolbarLegend' => [
        'load--yellow' => Yii::t('app', "Load is 'Dropped', that is, it has been picked up by a driver/carrier and dropped at a location other than the final consignee."),
        'load--blue' => Yii::t('app', "Load is 'Reserved' for a driver or carrier."),
        'load--light-gray' => Yii::t('app', "'Possible load - a 'Possible' load is just that - one that may or may not happen.'"),
        'load--gray' => Yii::t('app', "'Pending' load - a 'Pending' load is visible only to the user who created it. A Pending load is one that you do not want released until a specific date, at which point the load becomes visible to all."),
        'load--light-orange' => Yii::t('app', "Load is 2-8 hours out from Pickup or Delivery."),
        'load--medium-orange' => Yii::t('app', "Load is 1-2 hours out from Pickup or Delivery."),
        'load--dark-orange' => Yii::t('app', "Load is within 1 hour of Pickup or Delivery."),
        'load--red' => Yii::t('app', "Load is 'Late' for Pickup or Delivery."),
        'load--white' => Yii::t('app', "Load is 'Available' and is not assigned nor has it been dropped."),
    ],
    'attributes' => [
        'data-dispatch' => Url::toRoute(['dispatch-load', 'id' => '{id}']),
        'data-summary' => Url::toRoute(['dispatch-summary', 'load' => '{id}']),
        'data-reserve' => Url::toRoute(['reserve-load', 'id' => '{id}']),
    ],
    'rowAttributes' => 12
]);

if ($dataProvider2->totalCount) {
    echo Grid::widget([
        'id' => 'enroute-loads',
        'dataProvider' => $dataProvider2,
        'columns' => [
            'id|title=Load number',
            'id|title=Pick Up date|rel=loadStops[0].available_from|date',
            'id|title=Pick Up time|rel=loadStops[0].time_from|time',
            new DataColumn([
                'title' => Yii::t('app', 'Delivery date'),
                'value' => function (Load $model) {
                    return $model->loadStops ? Yii::$app->formatter->asDate($model->loadStops[count($model->loadStops) - 1]->available_from) : '';
                }
            ]),
            new DataColumn([
                'title' => Yii::t('app', 'Delivery time'),
                'value' => function (Load $model) {
                    return $model->loadStops ? Yii::$app->formatter->asTime($model->loadStops[count($model->loadStops) - 1]->time_from) : '';
                }
            ]),
            new DataColumn([
                'title' => Yii::t('app', 'Pick up city'),
                'value' => function (Load $model) {
                    return $model->loadStops ? $model->loadStops[0]->getCity() : '';
                }
            ]),
            new DataColumn([
                'title' => Yii::t('app', 'Pick up state'),
                'value' => function (Load $model) {
                    return $model->loadStops ? $model->loadStops[0]->getStateCode() : '';
                }
            ]),
            new DataColumn([
                'title' => Yii::t('app', 'Delivery city'),
                'value' => function (Load $model) {
                    return $model->loadStops ? $model->loadStops[count($model->loadStops) - 1]->getCity() : '';
                }
            ]),
            new DataColumn([
                'title' => Yii::t('app', 'Delivery state'),
                'value' => function (Load $model) {
                    return $model->loadStops ? $model->loadStops[count($model->loadStops) - 1]->getStateCode() : '';
                }
            ]),
            'id|title=Truck unit|rel=dispatchAssignment.unit_id',
            'id|title=Driver name|rel=dispatchAssignment.driver.getFullName()',
            new ActionColumn([
                'html' => function (Load $model) {
                    return Html::a('<i class="fas fa-edit"></i>', ['edit', 'id' => $model->id], ['class' => 'px-1', 'data-tooltip' => 'tooltip', 'data-placement' => 'top', 'title' => Yii::t('app', 'Edit')]);
                }
            ]),
        ],
        'attributes' => [
            'data-arrive' => Url::toRoute(['arrive', 'id' => '{id}']),
            'data-dispatch' => Url::toRoute(['dispatch-load', 'id' => '{id}']),
            'data-summary' => Url::toRoute(['dispatch-summary', 'load' => '{id}']),
            'data-message' => Url::toRoute(['chat-message/create', 'id' => '{id}']),
        ]
    ]);
}