<?php
/**
 * /var/www/html/frontend/runtime/giiant/a0a12d1bd32eaeeb8b2cff56d511aa22
 *
 * @package default
 */

use common\models\Load;
use common\widgets\DataTables\ActionColumn;
use common\widgets\DataTables\DataColumn;
use yii\helpers\Html;

$this->title = Yii::t('app', 'Arrived Loads');
$this->params['breadcrumbs'][] = $this->title;
?>
  <div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><?php echo Yii::t('app', 'Arrived Loads') ?></h1>
  </div>
<?php

echo common\widgets\DataTables\Grid::widget([
    'id' => 'available-loads',
    'dataProvider' => $dataProvider,
    'columns' => [
        'id',
        new DataColumn([
            'attribute' => 'booked_by',
            'value' => function ($model) {
                return $model->bookedBy->username;
            }
        ]),
        new DataColumn([
            'attribute' => 'bill_to',
            'value' => function ($model) {
                if ($rel = $model->billTo) {
                    return $rel->name;
                }
                return '';
            }
        ]),
        new DataColumn([
            'title' => Yii::t('app', 'Shipper'),
            'value' => function ($model) {
                /** @var Load $model */
                $stops = $model->getLoadStopsOrdered();
                $firstStop = $stops[0];
                return $firstStop->getCompanyName();
            }
        ]),
        'bill_miles|int',
        new DataColumn([
            'attribute' => 'office_id',
            'value' => function ($model) {
                if ($rel = $model->office) {
                    return $rel->_label;
                }
                return '';
            },
            'filterable' => true,
        ]),
        new DataColumn([
            'attribute' => 'type_id',
            'value' => function ($model) {
                if ($rel = $model->type) {
                    return $rel->_label;
                }
                return '';
            },
            'filterable' => true,
        ]),
        'notes',
        new DataColumn([
            'title' => Yii::t('app', 'Delivered by'),
            'value' => function ($model) {
                /** @var Load $model */
                $da = $model->dispatchAssignment;
                return $da->driver ? $da->driver->get_label() : "";
            }
        ]),
        new DataColumn([
            'title' => Yii::t('app', 'Arrived'),
            'value' => function ($model) {
                /** @var Load $model */
                $stops = $model->getLoadStopsOrdered();
                $lastStop = $stops[count($stops) - 1];
                return Yii::$app->formatter->asDate($model->arrived_date ? $model->arrived_date : $lastStop->available_from, "php:Y/m/d");
            }
        ]),
        new ActionColumn([
            'html' => function ($model) {
                return Html::a('<i class="fas fa-edit"></i>', ['edit', 'id' => $model->id], ['class' => 'px-1', 'data-tooltip' => 'tooltip', 'data-placement' => 'top', 'title' => Yii::t('app', 'Edit')]);
            }
        ])
    ],
    'rightToolbarHtml' => '
<div class="dt-toolbar-actions js-daterange d-flex" data-column-index="9">
    <div class="dropdown">
        <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown"><i class="fas fa-filter"></i></button>
        <div class="dropdown-menu">
            <a class="dropdown-item js-daterange-this-month" href="#">This Month</a>
            <a class="dropdown-item js-daterange-last-month" href="#">Last Month</a>
            <a class="dropdown-item js-daterange-this-quarter" href="#">This Quarter</a>
            <a class="dropdown-item js-daterange-last-quarter" href="#">Last Quarter</a>
            <a class="dropdown-item js-daterange-this-year" href="#">This Year</a>
            <a class="dropdown-item js-daterange-last-year" href="#">Last Year</a>
            <a class="dropdown-item js-daterange-today" href="#">Today</a>
        </div>
    </div>
    <input class="form-control js-daterange-min" type="text" name="min" placeholder="from date">
    <input class="form-control js-daterange-max" type="text" name="max" placeholder="to date">
</div>',
]);